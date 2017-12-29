<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\admin\controller;
use app\admin\model\ConstructionModel;


class Construction extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    //保存上传附件信息
    public function saveVideoInfo()
    {
        $video = new ConstructionModel();

        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'name' => $param['name'],
                'date' => date("Y-m-d H:i:s"),
                'path' => $param['path']
            ];
            $flag = $video->insertVideo($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);

        }
    }

    //返回视频url
    public function getUrl()
    {
        $video = new ConstructionModel();

        $param = input('post.');
        if(request()->isAjax()){
            $data = $video->getOne($param['id']);

            return json(['url' => substr($data['path'],1)]);

        }
    }


    /**
     * [删除视频]
     */
    public function videoDel()
    {
        $param = input('post.');
        if(request()->isAjax()) {
            $id = $param['id'];
            $video = new ConstructionModel();
            $data = $video->getOne($id);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $video->delVideo($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

}