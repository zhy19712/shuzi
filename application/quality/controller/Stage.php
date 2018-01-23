<?php
/**
 * Created by PhpStorm.
 * User: zhuangyf
 * Date: 2018/1/9
 * Time: 19:02
 */

namespace app\quality\controller;

use app\admin\controller\Base;
use app\quality\model\ProjectStageModel;

class Stage extends Base
{
    public function index()
    {
        $stage = new ProjectStageModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $stage->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    public function stageDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new ProjectStageModel();
        $param = $attachment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['name'] . '.' . substr(strrchr($filePath, '.'), 1); ;
        if(file_exists($filePath)) {
            $file = fopen($filePath, "r"); //   打开文件

            //输入文件标签
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

    public function stageDel()
    {
        $attachment = new ProjectStageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $new_data = [
                'id' => $param['id'],
                'owner' => '',
                'date' => '',
                'path' => '',
                'status' => '',
                'filename' => ''
            ];
            $flag = $attachment->editStage($new_data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function attachmentPreview()
    {
        $attachment = new ProjectStageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $attachment->getOne($param['id']);
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


//    public function stageEditDel()
//    {
//        $attachment = new ProjectStageModel();
//        if(request()->isAjax()) {
//            $param = input('post.');
//            $data = $attachment->getOne($param['id']);
//            $path = $data['path'];
//            unlink($path); //删除文件
//            return json([ 'msg' => 'success']);
//        }
//    }

//    //编辑，没有替换附件时保存上传附件信息
//    public function editStageNoUpload()
//    {
//        $attachment = new ProjectStageModel();
//
//        $param = input('post.');
//        if(request()->isAjax()){
//            $data = [
//                'id' => $param['uid'],
//                'owner' => session('username'),
//                'date' => date("Y-m-d H:i:s"),
//                'year' => $param['year'],
//                'season' =>  $param['season'],
//                'name' =>  $param['uname'],
//            ];
//            $flag = $attachment->editStage($data);
//            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
//        }
//    }
}