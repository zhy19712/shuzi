<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:06
 */
namespace app\safety\controller;

use app\admin\controller\Base;

class Idenregstandard extends Base
{
    public function index()
    {
        return $this ->fetch();
    }
}