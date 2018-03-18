<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 16:30
 */
//应急评估
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EmergencyreviseModel;
use think\Db;
use think\Loader;

class Emergencyrevise extends Base
{
    /*
    * 获取一条应用设备信息
    * @return mixed|\think\response\Json
    */
    public function index()
    {
        if(request()->isAjax()){
            $emergencyrevise = new EmergencyreviseModel();
            $param = input('post.');
            $data = $emergencyrevise->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 删除一条应急评估信息
     * @return mixed|\think\response\Json
     */
    public function equipmentDel()
    {
        $emergencyrevise = new EmergencyreviseModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $emergencyrevise->delEmergencyrevise($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


}