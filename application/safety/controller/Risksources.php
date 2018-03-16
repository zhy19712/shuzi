<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 重大危险源识别与管理
use app\admin\controller\Base;
use app\admin\model\ContractModel;
use app\safety\model\EducationModel;
use app\safety\model\RiskSourcesModel;
use think\Db;
use think\Loader;

class Risksources extends Base
{
    /**
     * 预览获取一条数据  或者  编辑获取一条数据
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $edu = new RiskSourcesModel();
            $data = $edu->getOne($param['id']);
            return json($data);
        }
        return $this ->fetch();
    }

    /**
     * 无文件上传的编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiEdit()
    {
        if(request()->isAjax()){
            $sdi = new RiskSourcesModel();
            $param = input('post.');
            $data = [
                'id' => $param['id'],
                'remark' => $param['remark']
            ];
            $flag = $sdi->editEdu($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function eduDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $edu = new RiskSourcesModel();
        $param = $edu->getOne($id);
        $filePath = $param['ma_path'];
        $fileName = $param['material_name'];
        // 如果是手动输入的名称，就有可能没有文件后缀
        $extension = get_extension($fileName);
        if(empty($extension)){
            $fileName = $fileName . '.' . substr(strrchr($filePath, '.'), 1);
        }

        if(file_exists($filePath)) {
            $file = fopen($filePath, "r"); //   打开文件
            //输入文件标签
            $fileName = iconv("utf-8","gb2312",$fileName);
            Header("Content-type:application/octet-stream ");
            Header("Accept-Ranges:bytes ");
            Header("Accept-Length:   " . filesize($filePath));
            Header("Content-Disposition:   attachment;   filename= " . $fileName);
            //   输出文件内容
            echo fread($file, filesize($filePath));
            fclose($file);
            exit;
        }
    }

    /**
     * 预览
     * @return \think\response\Json
     * @author hutao
     */
    public function eduPreview()
    {
        $edu = new RiskSourcesModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $edu->getOne($param['id']);
            if($param['type'] == '1'){ // type 1 表示的是 培训材料文件 2 表示培训记录文件
                $path = $data['ma_path'];
            }else{
                $path = $data['re_path'];
            }
            $extension = strtolower(get_extension(substr($path,1)));
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(!file_exists($pdf_path)){
                if($extension === 'doc' || $extension === 'docx' || $extension === 'txt'){
                    doc_to_pdf($path);
                }else if($extension === 'xls' || $extension === 'xlsx'){
                    excel_to_pdf($path);
                }else if($extension === 'ppt' || $extension === 'pptx'){
                    ppt_to_pdf($path);
                }else if($extension === 'pdf'){
                    $pdf_path = $path;
                }else{
                    $code = 0;
                    $msg = '文不支持的件格式';
                }
                return json(['code' => $code, 'path' => substr($pdf_path,1), 'msg' => $msg]);
            }else{
                return json(['code' => $code,  'path' => substr($pdf_path,1), 'msg' => $msg]);
            }
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function eduDel()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $edu = new RiskSourcesModel();
            $flag = $edu->delEdu($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    /**
     * 初始化左侧节点树
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author hutao
     */
    public function getSegment()
    {
        if(request()->isAjax()){
            $con = new ContractModel();
            $data = $con->getBiaoduanName(2); // 2 表示页面有2个一一级节点
            return json($data);
        }
    }

}