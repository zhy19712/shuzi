<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\quality\controller;
use app\admin\controller\Base;
use app\quality\model\ConstructionModel;
use think\Db;


class Construction extends Base
{
    public function index()
    {
        $search_name = input('key');
        $map = [];
        if($search_name && $search_name!==""){
            $map['search_name'] = ['like',"%" . $search_name . "%"];
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('list_rows');// 获取总条数
        $count = Db::name('video')->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $article = new ConstructionModel();
        $lists = $article->getVideoByWhere($map, $Nowpage, $limits);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $search_name);
        if(input('get.page')){
            return json($lists);
        }
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

            return json(['code' => 1, 'url' => substr($data['path'],1)]);

        }
    }


    public function videoEdit()
    {
        $video = new ConstructionModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $video->getOne($param['id']);
            $name = $data['name'];
            return json(['data' => $name,  'msg' => "success"]);
        }
    }
    public function videoEditSave()
    {
        $video = new ConstructionModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $video->editVideo($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
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
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            $flag = $video->delVideo($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function videoPlay()
    {
        return $this->fetch();
    }

}