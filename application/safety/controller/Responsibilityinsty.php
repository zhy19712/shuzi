<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 9:49
 */

namespace app\safety\controller;

use app\admin\controller\Base;

class Responsibilityinsty extends Base
{
    public  function  index()
    {
        return $this ->fetch();
    }
}