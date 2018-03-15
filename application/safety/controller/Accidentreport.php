<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/15
 * Time: 17:27
 */
//事故报告
namespace app\safety\controller;

use app\admin\controller\Base;

class Accidentreport extends Base
{
    /*
    * 获取一条事故报告信息
    */
    public function index()
    {
        return $this->fetch();
    }
}