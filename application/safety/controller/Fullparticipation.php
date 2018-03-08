<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 15:31
 */
//全员参与->安全生产责任制
namespace app\safety\controller;

use app\admin\controller\Base;

class Fullparticipation extends Base
{
    /*
     * 获取一条安全生产责任制信息
    */
    public function index()
    {
        return $this->fetch();
    }
}
