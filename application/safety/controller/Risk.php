<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/15
 * Time: 17:14
 */
namespace app\safety\controller;

use app\admin\controller\Base;

class Risk extends Base
{
    /**
     * 安全排查
     */
    public function index()
    {
        return  $this->fetch();
    }

    /**
     * 一岗双责
     */
    public function duty()
    {
        return $this->duty();
    }
}