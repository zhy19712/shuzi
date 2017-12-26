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
            $nodeStr1 = $node1->getNodeInfo();
            $nodeStr2 = $node1->getNodeInfo();
            $nodeStr = "[" . $nodeStr1 . $nodeStr2 . "]";
            return json($nodeStr);}
        else
            return $this->fetch();
    }

}