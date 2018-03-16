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
use app\safety\model\EmergencyreviseModel;

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
     *编辑/修订一条应急预案信息
     */
    public function emergencyRevise()
    {
        $emergency = new EmergencyplanModel();
        $revise = new EmergencyreviseModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'preplan_file_name' => $param['preplan_file_name'],
                'preplan_number' => $param['preplan_number'],
                'version_number' => $param['version_number'],
                'alternative_version' => $param['alternative_version'],
                'preplan_state' => $param['aid'],
                'path' => $param['path'],
                'remark' => $param['remark']
            ];

            $data1 = [
//                'id' => $param['aid'],
                'preplan_file_name' => $param['preplan_file_name'],
                'preplan_number' => $param['preplan_number'],
                'version_number' => $param['version_number'],
                'alternative_version' => $param['alternative_version'],
                'preplan_state' => $param['aid'],
                'path' => $param['path'],
                'remark' => $param['remark']
            ];

            $flag = $emergency->editEmergencyplan($data);
            $flag1 = $revise->insertEmergencyrevise($data1);
            if($flag['code'] && $flag1['code'])
            {
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }


        }
    }
}