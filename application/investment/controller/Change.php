<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/29
 * Time: 9:11
 */

namespace app\investment\controller;


use app\admin\controller\Base;

class Change extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}