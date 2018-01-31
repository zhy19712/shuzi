<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 15:31
 */

namespace app\archive\controller;


use app\admin\controller\Base;

class Incomingdesign extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}