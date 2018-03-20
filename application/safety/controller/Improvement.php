<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:56
 */

// 持续改进
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\ImprovementModel;

class Improvement extends Base
{
    /**
     *  编辑时 根据 id 编号获取一条数据
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public  function  index()
    {
        if(request()->isAjax()){
            $improve = new ImprovementModel();
            $param = input('post.');
            $data = $improve->getOne($param['major_key']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * 无文件上传时的编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function improveEdit()
    {
        $improve = new ImprovementModel();
        $param = input('post.');
        if(request()->isAjax()){
            $is_exist = $improve->getOne($param['major_key']);
            if(empty($is_exist)){
                return json(['code' => '-1', 'msg' => '不存在的编号，请刷新当前页面']);
            }
            $flag = $improve->editImprovement($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function improveDel()
    {
        if(request()->isAjax()){
            $improve = new ImprovementModel();
            $param = input('post.');
            $flag = $improve->delImprovement($param['major_key']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function improveDownload()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $major_key = input('param.major_key');
        $improve = new ImprovementModel();
        $param = $improve->getOne($major_key);
        $filePath = $param['path'];
        $fileName = $param['ment_name'];
        // 如果是手动输入的名称，就有可能没有文件后缀
        $extension = get_extension($fileName);
        if(empty($extension)){
            $fileName = $fileName . '.' . substr(strrchr($filePath, '.'), 1);
        }
        $file = fopen($filePath, "r"); //   打开文件
        if(file_exists($filePath)){
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
        }else{
            return json(['code' => '-1','msg' => '文件不存在']);
        }
    }

    /**
     * 预览
     * @return \think\response\Json
     * @author hutao
     */
    public function improvePreview()
    {
        $improve = new ImprovementModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $improve->getOne($param['major_key']);
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

}