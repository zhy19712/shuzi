<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/16
 * Time: 16:47
 */
//应急处置
namespace app\safety\controller;

use app\admin\controller\Base;


class Emergencydisposal extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}