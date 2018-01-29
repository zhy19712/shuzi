<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/29
 * Time: 8:42
 */

namespace app\safety\controller;


use app\admin\controller\Base;

class Improvement extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}