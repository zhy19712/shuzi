<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:34
 */

namespace app\quality\controller;

use app\admin\controller\Base;
use app\admin\model\ProjectModel;
use app\quality\model\PrototypeAttachmentModel;
use app\quality\model\PrototypeListModel;
use app\quality\model\PrototypeModel;
use app\admin\model\UserModel;
use app\admin\model\UserType;

class Prototype extends Base
{
    public function index()
    {
        $prototype = new PrototypeModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $prototype->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    public function prototypeDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new PrototypeModel();
        $param = $attachment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['name'] . '.' . substr(strrchr($filePath, '.'), 1); ;
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

    public function prototypeDel()
    {
        $attachment = new PrototypeModel();
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
            $flag = $attachment->delPrototype($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function prototypePreview()
    {
        $attachment = new ProjectModel();
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

    public function prototypeEditDel()
    {
        $attachment = new PrototypeModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            $newData = [
                'id' => $data['id'],
                'filename' => null,
                'path' => null
            ];
            $flag = $attachment->editPrototype($newData);
            return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
        }
    }

    //编辑，没有替换附件时保存上传附件信息
    public function editPrototypeNoUpload()
    {
        $attachment = new PrototypeModel();

        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['uid'],
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'year' => $param['year'],
                'season' =>  $param['season'],
                'name' =>  $param['uname'],
            ];
            $flag = $attachment->editPrototype($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    public function prototypeListEdit()
    {
        $prototype = new PrototypeListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $prototype->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    public function prototypeListAdd()
    {
        $prototype = new PrototypeListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $param['date'] = date('Y-m-d');
            if(empty($param['id']))
            {
                $flag = $prototype->insertPrototypeList($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $prototype->editPrototypeList($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function prototypeListDel()
    {
        $prototype = new PrototypeListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $prototype->delPrototypeList($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    //从组织机构及用户树中选择负责人
    public function getOwner(){
        $node1 = new UserType();
        $node2 = new UserModel();
        $nodeStr1 = $node1->getNodeInfo_1();
        $nodeStr2 = $node2->getNodeInfo_2();
        $nodeStr = "[" . substr($nodeStr1 . $nodeStr2, 0, -1) . "]";
        return json($nodeStr);
    }

    public function prototypeAttachmentEditDel()
    {
        $attachment = new PrototypeAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            $newData = [
                'id' => $data['id'],
                'name' => null,
                'path' => null
            ];
            $flag = $attachment->editAttachment($newData);
            return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
        }
    }

    //编辑，没有替换附件时保存上传附件信息
    public function editPrototypeAttachmentNoUpload()
    {
        $attachment = new PrototypeAttachmentModel();

        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['uid'],
                'group_id' => $param['group_id'],
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'remark' =>  $param['remark'],
                'table_name' =>  $param['table_name'],
            ];
            $flag = $attachment->editAttachment($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function attachmentEdit()
    {
        $attachment = new PrototypeAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
    }

    public function attachmentDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new PrototypeAttachmentModel();
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

    public function attachmentDel()
    {
        $attachment = new PrototypeAttachmentModel();
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
            $flag = $attachment->delAttachment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function attachmentPreview()
    {
        $attachment = new PrototypeAttachmentModel();
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

    //检查按钮，将不符合的条目退回到实施表中
    public function sendBack()
    {
        $check = new PrototypeAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $check ->getOne($param['id']);
            $path = $data['path'];

            $path_copy = $data['path'] . 'bak';
            if (file_exists($path) == false)
            {
                die ('文件不在,无法复制');
            }else{
                copy($path, $path_copy);
            }

            $newData = [
                'group_id' => $data['group_id'],
                'table_name' => 'bhg',
                'path' => $path_copy,
                'name' => $data['name'],
                'owner' => $data['owner'],
                'date' => $data['date'],
                'remark' => $data['remark']
            ];
            $check->insertAttachment($newData);
            $param['table_name'] = 'ss';
            $flag = $check->editAttachment($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

    }
}