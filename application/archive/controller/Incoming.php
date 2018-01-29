<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/29
 * Time: 9:29
 */

namespace app\archive\controller;


use app\admin\controller\Base;

class Incoming extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}