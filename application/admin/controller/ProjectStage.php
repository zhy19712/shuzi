<?php
/**
 * Created by PhpStorm.
 * User: zhuangyf
 * Date: 2018/1/9
 * Time: 19:02
 */

namespace app\admin\controller;


use app\admin\model\ProjectStageModel;

class ProjectStage extends Base
{
    public function index()
    {
        $stage = new ProjectStageModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $stage->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function stageDownload()
    {
        $id = input('param.id');
        $attachment = new ProjectStageModel();
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

    public function stageDel()
    {
        $attachment = new ProjectStageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $attachment->delStage($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function stageEditDel()
    {
        $attachment = new ProjectStageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $attachment->getOne($param['id']);
            $path = $data['path'];
            unlink($path); //删除文件
            return json([ 'msg' => 'success']);
        }
    }

    //编辑，没有替换附件时保存上传附件信息
    public function editStageNoUpload()
    {
        $attachment = new ProjectStageModel();

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
            $flag = $attachment->editStage($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}