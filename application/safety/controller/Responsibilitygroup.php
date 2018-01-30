<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 14:10
 */

namespace app\safety\controller;

//机构职责
use app\admin\controller\Base;

class Responsibilitygroup extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}