<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:34
 */

namespace app\quality\controller;
use app\admin\controller\Base;
use app\quality\model\ProcedureAttachmentModel;
use app\quality\model\ProcedureListModel;
use app\quality\model\ProcedureListSublistModel;
use app\quality\model\ProcedureModel;
use app\admin\model\UserModel;
use app\admin\model\UserType;

class Procedure extends Base
{
    public function index()
    {
        $procedure = new ProcedureModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
//
//    public function procedureEdit()
//    {
//        $procedure = new ProcedureModel();
//        if(request()->isAjax()){
//           if(!empty($param['id']))
//            {
//                $flag = $procedure->editProcedure($param);
//                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
//            }
//
//        }
//    }

    public function procedureDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new ProcedureModel();
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

    public function procedureDel()
    {
        $attachment = new ProcedureModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            $flag = $attachment->delProcedure($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function procedurePreview()
    {
        $attachment = new ProcedureModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            $extension = get_extension(substr($path,1));
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if($extension == 'doc' || $extension == 'docx' || $extension == 'txt'){
                doc_to_pdf($path);
            }else if($extension == 'xls' || $extension == 'xlsx'){
                excel_to_pdf($path);
            }else if($extension == 'ppt' || 'pptx'){
                ppt_to_pdf($path);
            }else if($extension == 'pdf'){
                $resutl = copy($path, $pdf_path);
                if($resutl){
                    return json(['code' => 1, 'path' => substr($pdf_path,1)]);
                }
            }else{
                return json(['code' => 0, 'path' => $pdf_path, 'msg' => '不支持的文件类型']);
            }

            if(file_exists($pdf_path)){
                return json(['code' => 1, 'path' => substr($pdf_path,1)]);
            }else{
                return json(['code' => 0, 'path' => substr($pdf_path,1), 'msg' => '文件预览失败']);
            }
        }
    }

    public function procedureEditDel()
    {
        $attachment = new ProcedureModel();
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
            $flag = $attachment->editProcedure($newData);
            return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
        }
    }

    //编辑，没有替换附件时保存上传附件信息
    public function editProcedureNoUpload()
    {
        $attachment = new ProcedureModel();

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
            $flag = $attachment->editProcedure($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    public function procedureListEdit()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure_list->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    public function procedureListAdd()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $param['date'] = date("Y-m-d H:i:s");
            if(empty($param['id']))
            {
                $flag = $procedure_list->insertProcedureList($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $procedure_list->editProcedureList($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function procedureListDel()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $procedure_list->delProcedureList($param['id']);
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

    public function procedureListSublistEdit()
    {
        $procedure_list_sublist = new ProcedureListSublistModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure_list_sublist->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    public function procedureListSublistAdd()
    {
        $procedure_list_sublist = new ProcedureListSublistModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $param['date'] = date("Y-m-d H:i:s");
                $flag = $procedure_list_sublist->insertProcedureListSublist($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $procedure_list_sublist->editProcedureListSublist($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function procedureListSublistDel()
    {
        $procedure_list_sublist = new ProcedureListSublistModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $procedure_list_sublist->delProcedureListSublist($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    public function procedureAttachmentEditDel()
    {
        $attachment = new ProcedureAttachmentModel();
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
    public function editProcedureAttachmentNoUpload()
    {
        $attachment = new ProcedureAttachmentModel();

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


    public function attachmentDel()
    {
        $attachment = new ProcedureAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            $flag = $attachment->delAttachment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    public function attachmentDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new ProcedureAttachmentModel();
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

    public function attachmentEdit()
    {
        $attachment = new ProcedureAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
    }

    //检查按钮，将不符合的条目退回到实施表中
    public function sendBack()
    {
        $check = new ProcedureAttachmentModel();
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