<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 14:11
 */

namespace app\safety\controller;

//生产投入
use app\admin\controller\Base;

class Responsibilityproduce extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}