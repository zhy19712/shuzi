<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:35
 */

namespace app\quality\controller;
use app\admin\controller\Base;
use app\quality\model\QCMemberModel;
use app\quality\model\QCModel;
use app\quality\model\QCAttachmentModel;
use app\quality\model\QCProblemModel;
use app\quality\model\QCStrategyModel;


class Qc extends Base
{
    public function index()
    {
        $qc = new QCModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function qcAdd()
    {
        $qc = new QCModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $qc->insertQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $qc->editQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function qcDel()
    {
        $qc = new QCModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $qc->delQc($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function memberEdit()
    {
        $qc = new QCMemberModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function memberAdd()
    {
        $qc = new QCMemberModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $qc->insertQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $qc->editQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function memberDel()
    {
        $qc = new QCMemberModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $qc->delQc($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function problemEdit()
    {
        $qc = new QCProblemModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function problemAdd()
    {
        $qc = new QCProblemModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $qc->insertQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $qc->editQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function problemDel()
    {
        $qc = new QCProblemModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $qc->delQc($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function strategyEdit()
    {
        $qc = new QCStrategyModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function strategyAdd()
    {
        $qc = new QCStrategyModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $qc->insertQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $qc->editQc($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function strategyDel()
    {
        $qc = new QCStrategyModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $qc->delQc($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    public function saveAttachmentInfo()
    {
        $attachment = new QCAttachmentModel();

        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))
            {
                if($param['table_name'] == 'ss' || $param['table_name'] == 'smyxzl' || $param['table_name'] == 'smyx' || $param['table_name'] == 'wjzl'){
                    $data = [
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'path' => $param['path'],
                        'name' => $param['name'],
                        'revision' => $param['revision'],
                        'group_id' => $param['group_id'],
                        'table_name' => $param['table_name']
                    ];
                }else{
                    $data = [
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'path' => $param['path'],
                        'name' => $param['name'],
                        'group_id' => $param['group_id'],
                        'table_name' => $param['table_name']
                    ];
                }
                $flag = $attachment->insertAttachment($data);
                $data_newer = $attachment->getImageId($param['group_id'], $param['table_name']);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg'], 'id' => $data_newer['id']]);
            }else{
                $data_older = $attachment->getOne($param['id']);
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }

                if($param['table_name'] == 'ss' || $param['table_name'] == 'smyxzl' || $param['table_name'] == 'smyx' || $param['table_name'] == 'wjzl'){
                    $data = [
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'path' => $param['path'],
                        'name' => $param['name'],
                        'revision' => $param['revision'],
                        'group_id' => $param['group_id'],
                        'table_name' => $param['table_name'],
                        'id' =>$param['id']
                    ];
                }else{
                    $data = [
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'path' => $param['path'],
                        'name' => $param['name'],
                        'group_id' => $param['group_id'],
                        'table_name' => $param['table_name'],
                        'id' =>$param['id']
                    ];
                }
                $flag = $attachment->editAttachment($data);
                $data_newer = $attachment->getImageId($param['group_id'], $param['table_name']);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg'], 'id' => $data_newer['id']]);
            }

        }
    }

    public function attachmentDownload()
    {
        $id = input('param.id');
        $attachment = new QCAttachmentModel();
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
        $qc = new QCAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            $path = $data['path'];
            if(file_exists($path)){
                unlink($path);
            }
            $flag = $qc->delAttachment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function getAttachmentInfo(){
        $param = input('post.');
        $group_id = $param['group_id'];
        $table_name1 = $param['table_name1'];
        $table_name2 = $param['table_name2'];
        $table_name3 = $param['table_name3'];
        $attachment = new QCAttachmentModel();
        $data = $attachment->getInfo($group_id, $table_name1,$table_name2,$table_name3);
        return $data;
    }

}