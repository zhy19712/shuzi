<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:23
 */
//职业健康
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\JobhealthModel;

class Jobhealth extends Base
{
    /*
     * [index 职业健康中的左边的分类节点]
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new JobhealthModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);

        }
        else
            return $this->fetch();
    }
}