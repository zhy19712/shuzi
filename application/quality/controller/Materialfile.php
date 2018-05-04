<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/5/4
 * Time: 10:59
 */

namespace app\quality\controller;


use app\admin\controller\Base;

/**
 * 试验资料
 * Class Materialfile
 * @package app\quality\controller
 */
class Materialfile extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    // 无文件上传编辑
    public function edit()
    {

    }


}