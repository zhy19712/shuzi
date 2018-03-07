<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:00
 */
namespace app\safety\controller;

use app\admin\controller\Base;

class Responsibilityculture extends Base
{
    public function index()
    {
        return $this ->fetch();
    }
}