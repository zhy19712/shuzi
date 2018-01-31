<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 15:16
 */

namespace app\safety\controller;


use app\admin\controller\Base;

class Educationpeople extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}