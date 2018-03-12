<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2018/3/12
 * Time: 15:12
 */

namespace app\safety\controller;


use app\admin\controller\Base;

//暂不开发 安全生产投入
class Safetyinvestment extends Base
{
    public function index()
    {
        return $this ->fetch();
    }

}