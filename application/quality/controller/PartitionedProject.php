<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\quality\controller;
use app\admin\controller\Base;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;

/**
 * 质量验收管理  --  分部工程
 * Class DataStatisticalAnalysis
 * @package app\quality\controller
 * @author hutao
 */
class PartitionedProject extends Base
{
    public function index()
    {
        return $this->fetch();
    }

}