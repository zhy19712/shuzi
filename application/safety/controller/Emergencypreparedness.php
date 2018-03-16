<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/15
 * Time: 19:09
 */
//应急准备
namespace app\safety\controller;

use app\admin\controller\Base;
use think\Db;
use think\Loader;
use app\safety\model\AccidentfileModel;

class Emergencypreparedness extends Base
{
    /*
     * 获取一条应急准备信息
    */
    public function index()
    {
        return $this->fetch();
    }
}