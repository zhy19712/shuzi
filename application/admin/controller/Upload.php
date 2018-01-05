<?php

namespace app\admin\controller;
use think\Controller;
use think\File;
use think\Request;
use app\admin\model\QCAttachmentModel;

class Upload extends Base
{
	//图片上传
    public function upload(){
       $file = request()->file('file');
       $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/images');
       if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }

    //会员头像上传
    public function uploadface(){
       $file = request()->file('file');
       $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/face');
       if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();

        }
    }

    //文件上传
    public function uploadfile(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/attachment');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }

    public function uploadtest(){
        $request_body = request()->param('sex');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/attachment');

        $data = json_decode($request_body);;
        if($info){
            return json(['data' => $request_body]);
        }else{
            echo $file->getError();
        }
    }

    public function uploadQC(){
        $qc = new QCAttachmentModel();
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $group_id = request()->param('group_id');
        $revision = request()->param('revision');
        $publish_date = request()->param('publish_date');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/attachment');
        if($info){
            $path = './uploads/attachment/' . $info->getFilename();
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'name' => $filename,
                    'revision' => $revision,
                    'group_id' => $group_id,
                    'table_name' => $table_name,
                    'publish_date' => $publish_date
                ];
                $flag = $qc->insertAttachment($data);
                $data_newer = $qc->getImageId($group_id, $table_name);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg'], 'id' => $data_newer['id']]);
            }else{
                $data_older = $qc->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'name' => $filename,
                    'revision' => $revision,
                    'group_id' => $group_id,
                    'table_name' => $table_name,
                    'publish_date' => $publish_date
                ];
                $flag = $qc->editAttachment($data);
                $data_newer = $qc->getImageId($group_id, $table_name);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg'], 'id' => $data_newer['id']]);
            }
        }else{
            echo $file->getError();
        }
    }



    //视频上传
    public function uploadvideo(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/video');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }
}