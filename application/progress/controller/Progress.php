<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/29
 * Time: 9:41
 */

namespace app\progress\controller;


use app\admin\controller\Base;

class Progress extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}