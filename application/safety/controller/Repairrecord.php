<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 14:15
 */
//车辆管理维修记录
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\RepairrecordModel;//维修
use think\Db;
use think\Loader;

class Repairrecord extends Base
{
    /*
     * 新增/编辑一条车辆管理维修记录
     */
    public function  repairrecordEdit()
    {
        $repairrecord = new RepairrecordModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))//id为空的时候为新增
            {
                $data = [
//                    'id' => $param['id'],
                    'pid' =>$param['pid'],//车辆管理表的id
                    'repair_time' => $param['repair_time'],//维修时间
                    'mileage_repair' => $param['mileage_repair'],//维修时公里数
                    'replacement_parts' => $param['replacement_parts'],//维修内容及更换的配件情况
                    'repair_place' => $param['repair_place'],//维修地点及厂家名称
                    'agent_person' => $param['agent_person'],//经办人
                    'approver_person' => $param['approver_person']//批准人
                ];
                $flag = $repairrecord->insertRepairrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $param['id'],
                    'repair_time' => $param['repair_time'],//维修时间
                    'mileage_repair' => $param['mileage_repair'],//维修时公里数
                    'replacement_parts' => $param['replacement_parts'],//维修内容及更换的配件情况
                    'repair_place' => $param['repair_place'],//维修地点及厂家名称
                    'agent_person' => $param['agent_person'],//经办人
                    'approver_person' => $param['approver_person']//批准人
                ];
                $flag = $repairrecord->editRepairrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条车辆管理维修记录
     */
    public function repairrecordDel()
    {
        $repairrecord = new RepairrecordModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $repairrecord->delRepairrecord($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}