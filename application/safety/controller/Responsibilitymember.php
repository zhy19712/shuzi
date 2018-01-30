<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 14:11
 */

namespace app\safety\controller;

//全员参与
use app\admin\controller\Base;

class Responsibilitymember extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}