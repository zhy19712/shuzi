<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 14:51
 */
//车辆管理保养记录
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\MaintenancerecordModel;//保养
use think\Db;
use think\Loader;

class Maintenancerecord extends Base
{
    /*
     * 获取一条车辆管理保养记录
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $maintenancerecord = new MaintenancerecordModel();
            $param = input('post.');
            $data = $maintenancerecord->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条车辆管理保养记录
     */
    public function  maintenancerecordEdit()
    {
        $maintenancerecord = new MaintenancerecordModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))//id为空的时候为新增
            {
                $data = [
//                    'id' => $param['id'],
                    'pid' =>$param['pid'],//车辆管理表的id
                    'maintenance_time' => $param['maintenance_time'],//保养时间
                    'mileage_maintenance' => $param['mileage_maintenance'],//保养时公里数
                    'replacement_parts' => $param['replacement_parts'],//保养内容及更换的配件情况
                    'repair_place' => $param['repair_place'],//保养地点及厂家名称
                    'agent_person' => $param['agent_person'],//经办人
                    'approver_person' => $param['approver_person']//批准人
                ];
                $flag = $maintenancerecord->insertMaintenancerecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $param['id'],
                    'maintenance_time' => $param['maintenance_time'],//保养时间
                    'mileage_maintenance' => $param['mileage_maintenance'],//保养时公里数
                    'replacement_parts' => $param['replacement_parts'],//保养内容及更换的配件情况
                    'repair_place' => $param['repair_place'],//保养地点及厂家名称
                    'agent_person' => $param['agent_person'],//经办人
                    'approver_person' => $param['approver_person']//批准人
                ];
                $flag = $maintenancerecord->editMaintenancerecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条车辆管理保养记录
     */
    public function maintenancerecordDel()
    {
        $maintenancerecord = new MaintenancerecordModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $maintenancerecord->delMaintenancerecord($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}