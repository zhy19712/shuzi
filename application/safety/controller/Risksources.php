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
use app\safety\model\RiskSourcesModel;

class Risksources extends Base
{
    /**
     * 编辑获取一条数据
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $sources = new RiskSourcesModel();
            $data = $sources->getOne($param['major_key']);
            return json($data);
        }
        return $this ->fetch();
    }

    /**
     * 无文件上传的编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function sourcesEdit()
    {
        if(request()->isAjax()){
            $sources = new RiskSourcesModel();
            $param = input('post.');
            $is_exist = $sources->getOne($param['major_key']);
            if(empty($is_exist)){
                return json(['code' => '-1', 'msg' => '不存在的编号，请刷新当前页面']);
            }
            $flag = $sources->editRiskSources($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function sourcesDownload()
    {
        $major_key = input('param.major_key');
        $sources = new RiskSourcesModel();
        $param = $sources->getOne($major_key);
        $filePath = $param['path'];
        if(!file_exists($filePath)){
            return json(['code' => '-1','msg' => '文件不存在']);
        }else if(request()->isAjax()){
            return json(['code'=>1]);
        } else{
            $fileName = $param['risk_name'];
            // 如果是手动输入的名称，就有可能没有文件后缀
            $extension = get_extension($fileName);
            if(empty($extension)){
                $fileName = $fileName . '.' . substr(strrchr($filePath, '.'), 1);
            }
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
    public function sourcesPreview()
    {
        $sources = new RiskSourcesModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $sources->getOne($param['major_key']);
            $path = $data['path'];
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
                    $msg = '不支持的文件格式';
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
    public function sourcesDel()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $sources = new RiskSourcesModel();
            $flag = $sources->delRiskSources($param['major_key']);
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
            $data = $con->getBiaoduanName(1); // 2 表示页面有2个一一级节点
            return json($data);
        }
    }

}