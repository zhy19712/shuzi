<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 14:12
 */

namespace app\safety\controller;

//信息化建设
use app\admin\controller\Base;

class Responsibilityinfo extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}