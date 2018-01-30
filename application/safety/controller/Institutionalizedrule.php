<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 15:03
 */

namespace app\safety\controller;


use app\admin\controller\Base;

class Institutionalizedrule extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}