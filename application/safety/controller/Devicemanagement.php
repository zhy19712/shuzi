<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 15:55
 */
//设备管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\DevicemanagementModel;
use think\Db;
use think\Loader;

class Devicemanagement extends Base
{
    /*
     * 获取一条设备管理信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $devicemanagement = new DevicemanagementModel();
            $param = input('post.');
            $data = $devicemanagement->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
}