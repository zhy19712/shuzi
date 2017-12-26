<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\admin\controller;
use think\Db;
use app\admin\model\AcceptanceModel;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;


class Acceptance extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $node1 = new DivideModel();
            $node2 = new ProjectModel();
            $nodeStr1 = $node1->getNodeInfo_4();
            $nodeStr2 = $node2->getNodeInfo_5();
            $nodeStr = "[" . substr($nodeStr1 . $nodeStr2, 0, -1) . "]";
            return json($nodeStr);}
        else
            return $this->fetch();
    }

    /**
     * [获取单元工程基础信息]
     * @return [type] [description]
     */
    public function fetchData()
    {
        $project = new ProjectModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $project->getOneProject($param['uid']);
            return json(['data' => $data, 'msg' => "success"]);
        }
    }


}