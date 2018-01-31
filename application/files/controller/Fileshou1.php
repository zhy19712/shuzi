<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/1/31
 * Time: 10:18
 */
namespace app\files\controller;


use app\admin\controller\Base;
class Fileshou1 extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}