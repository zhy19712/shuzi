<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 15:20
 */

namespace app\safety\controller;


use app\admin\controller\Base;

class Fieldhealth extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}