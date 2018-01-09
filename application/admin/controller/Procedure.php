<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:34
 */

namespace app\admin\controller;


use app\admin\model\ProcedureAttachmentModel;
use app\admin\model\ProcedureListModel;
use app\admin\model\ProcedureListSublistModel;
use app\admin\model\ProcedureModel;
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
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

//    public function procedureAdd()
//    {
//        $procedure = new ProcedureModel();
//        if(request()->isAjax()){
//            $param = input('post.');
//            if(empty($param['id']))
//            {
//                $flag = $procedure->insertProcedure($param);
//                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
//            }
//            else if(!empty($param['id']))
//            {
//                $flag = $procedure->editProcedure($param);
//                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
//            }
//
//        }
//    }

    public function procedureDownload()
    {
        $id = input('param.id');
        $attachment = new ProcedureModel();
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

    public function procedureDel()
    {
        $qc = new ProcedureModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $qc->delProcedure($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function procedureListEdit()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure_list->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function procedureListAdd()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
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
            return json(['data' => $data]);
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

    public function attachmentDownload()
    {
        $id = input('param.id');
        $attachment = new ProcedureAttachmentModel();
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

    public function attachmentDel()
    {
        $attachment = new ProcedureAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $attachment->delAttachment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    //检查按钮，将不符合的条目退回到实施表中
    public function sendBack()
    {
        $check = new ProcedureAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $param['table_name'] = 'ss';
            $flag = $check->editAttachment($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

    }
}