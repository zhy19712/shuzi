<?php
/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 2018/3/12
 * Time: 13:40
 */

namespace app\safety\controller;

use app\admin\controller\Base;

class Personneltrain extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}