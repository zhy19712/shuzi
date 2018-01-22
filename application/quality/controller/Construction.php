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
        if(request()->isAjax()){
            $map = [];
            $param = input('post.');
            $nowPage = $param['page'] ? $param['page']:1;
            $searchName = $param['searchName'];
            if($searchName && $searchName!==""){
                $map['name'] = ['like',"%" . $searchName . "%"];
            }
            $limits = 6;// 每页显示总条数
            $count = Db::name('video')->where($map)->count();
            $allpage = intval(ceil($count / $limits)); // 总页数
            $article = new ConstructionModel();
            $lists = $article->getVideoByWhere($map, $nowPage, $limits);
            return json(['num' => $count,'allpage'=> $allpage, 'list' => $lists]);
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
            $idarr = explode(',',$param['id_arr']);
            $video = new ConstructionModel();
            $data = $video->getPathArr($idarr);
            foreach ($data as $k=>$v){
                if(file_exists($v)){
                    unlink($v); //删除文件
                }
            }
            $flag = $video->delVideo($idarr);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function videoPlay()
    {
        return $this->fetch();
    }

}