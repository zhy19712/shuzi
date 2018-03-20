<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:23
 */
//现场管理->作业安全
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\JobsafetyModel;
use app\safety\model\EquipmentCheckAcceptModel;

class Jobsafety extends Base
{
    /*
     * 作业安全左边的树状结构
     */
    public function index()
    {
        if(request()->isAjax()){
            $node = new JobsafetyModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);
        }
        else
            return $this->fetch();
    }
}