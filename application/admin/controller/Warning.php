<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/5
 * Time: 9:10
 */

namespace app\admin\controller;
use app\admin\model\ProjectModel;

class Warning extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}