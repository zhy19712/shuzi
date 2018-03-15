<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:00
 */
//事故报告
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\AccidentreportModel;

class Accidentreport extends Base
{
    /*
     * 获取一条事故报告信息
    */
    public function index()
    {
        if(request()->isAjax()){
            $accident= new AccidentreportModel();
            $param = input('post.');
            $data = $accident->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
    /*
     *编辑一条事故报告信息
    */
    public function accidentEdit()
    {
        $accident = new AccidentreportModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'number' =>$param['number'],
                'remark' => $param['remark']
            ];
            $flag = $accident->editAccidentreport($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     *下载一条事故报告信息
    */
    public function accidentDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $accident = new AccidentreportModel();
        $param = $accident->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['name'];
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
    /*
     *删除一条事故报告信息
    */
    public function cultureDel()
    {
        $accident = new AccidentreportModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $accident->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $accident->delAccidentreport($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     *预览一条安全文明建设信息
    */
    public function accidentPreview()
    {
        $accident = new AccidentreportModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $accident->getOne($param['id']);
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