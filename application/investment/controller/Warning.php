<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/29
 * Time: 9:12
 */

namespace app\investment\controller;


use app\admin\controller\Base;

class Warning extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}