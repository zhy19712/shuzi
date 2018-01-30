<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 14:11
 */

namespace app\safety\controller;

//安全文化建设
use app\admin\controller\Base;

class Responsibilityculture extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}