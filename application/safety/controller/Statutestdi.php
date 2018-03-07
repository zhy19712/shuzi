<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:53
 */
//法规标准识别
namespace app\safety\controller;

use app\admin\controller\Base;

class Statutestdi extends Base
{
    public  function  index()
    {
        return $this ->fetch();
    }
}