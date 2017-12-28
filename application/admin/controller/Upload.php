<?php

namespace app\admin\controller;
use think\Controller;
use think\File;
use think\Request;
use app\admin\model\ProjectAttachmentModel;

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
        $attachment = new ProjectAttachmentModel();
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/attachment');
        if($info){
            $param = [       //接受传递的参数
                'owner' => session('username')

            ];
            $filename =  $info->getSaveName();
            $username = session('username');


        }else{
            echo $file->getError();
        }
    }

}