<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/5
 * Time: 9:22
 */

namespace app\admin\controller;


class Acceptancestage extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}