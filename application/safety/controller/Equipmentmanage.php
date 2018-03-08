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

class Equipmentmanage extends Base
{
    /*
     * 获取一条安全生产责任制信息
    */
    public function index()
    {
        return $this->fetch();
    }
}