<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/29
 * Time: 9:13
 */

namespace app\investment\controller;


use app\admin\controller\Base;

class Analysis extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}