<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:23
 */
//现场管理->作业安全
namespace app\safety\controller;

use app\admin\controller\Base;

class Jobsafety extends Base
{
    /*
     * 获取一条特种作业人员
    */
    public function index()
    {
        return $this->fetch();
    }
}