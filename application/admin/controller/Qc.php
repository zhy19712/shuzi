<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:35
 */

namespace app\admin\controller;


class Qc extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}