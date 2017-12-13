<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/13
 * Time: 8:53
 */

namespace app\admin\controller;
use app\admin\model\ContractModel;
use think\Db;

class contract extends Base
{
    public function index()
    {


        return $this->fetch();
    }

}