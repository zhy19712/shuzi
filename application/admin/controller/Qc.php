<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:35
 */

namespace app\admin\controller;
use app\admin\model\QCModel;
use app\admin\model\QCAttachmentModel;


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

    public function saveAttachmentInfo()
    {
        $attachment = new QCAttachmentModel();

        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'path' => $param['path'],
                'filename' => $param['filename'],
                'type' => $param['type'],
                'revision' => $param['revision'],
                'group_id' => $param['group_id'],
                'phase_id' => $param['phase_id']
            ];
            $flag = $attachment->insertAttachment($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function attachmentDownload()
    {
        $id = input('param.id');
        $attachment = new QCAttachmentModel();
        $param = $attachment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['filename'];
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
        $qc = new QCAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $qc->getOne($param['id']);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $qc->delAttachment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

}