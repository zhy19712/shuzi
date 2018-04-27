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
 *  数据统计分析
 * == 开挖工程
 * == 支护工程
 * == 混凝土工程
 * == 排水孔
 * Class DataStatisticalAnalysis
 * @package app\quality\controller
 * @author hutao
 */
class DataStatisticalAnalysis extends Base
{
    public function index()
    {
        return $this->fetch();
    }

}