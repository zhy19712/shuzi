<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/15
 * Time: 19:09
 */
//警示标志
namespace app\safety\controller;

use app\admin\controller\Base;
use think\Db;
use think\Loader;
use app\safety\model\WarningsignModel;

class Warningsign extends Base
{
    /*
     * [index 设置机构中左边的分类节点]
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new WarningsignModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);

        }
        else
            return $this->fetch();
    }
}