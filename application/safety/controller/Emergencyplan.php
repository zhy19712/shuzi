<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/16
 * Time: 15:42
 */
//应急预案
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EmergencyplanModel;

class Emergencyplan extends Base
{
    /*
     * 获取一条应急预案信息
     */
    public function index()
    {
        if(request()->isAjax()){
            $emergency= new EmergencyplanModel();
            $param = input('post.');
            $data = $emergency->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     *编辑一条安全文明建设信息
     */
    public function emergencyEdit()
    {
        $emergency = new EmergencyplanModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'remark' => $param['remark']
            ];
            $flag = $emergency->editEmergencyplan($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}