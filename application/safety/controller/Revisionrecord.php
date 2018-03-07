<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:56
 */
//修编记录
//法规标准识别
namespace app\safety\controller;

use app\admin\controller\Base;

class Revisionrecord extends Base
{
    public  function  index()
    {
        return $this ->fetch();
    }
}