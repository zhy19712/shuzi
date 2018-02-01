<?php

namespace app\files\controller;
use app\admin\controller\Base;
use app\files\model\FileshumaModel;

class Upload extends Base
{
    //文件上传
    public function uploadphoto(){
        $attachment = new FileshumaModel();
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/fileshuma');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/fileshuma/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'path' => $path,
                'name' => $filename,
                'filename' => $filename,
                'time' => request()->param('time'),
                'address' => request()->param('address'),
                'user' => request()->param('user'),
                'deed' => request()->param('deed'),
                'background' => request()->param('background'),
                'photographer' => request()->param('photographer'),
            ];
            $flag = $attachment->insertFileshuma($data);
            return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
        }else{
            echo $file->getError();
        }
    }
}