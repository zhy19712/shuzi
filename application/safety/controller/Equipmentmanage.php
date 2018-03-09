<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:11
 */
//现场管理->设备设施管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EquipmentModel;
class Equipmentmanage extends Base
{
    /*
     * 设备设施管理页面左边的树状结构
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new EquipmentModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);
        }
        else
            return $this->fetch();
    }
}