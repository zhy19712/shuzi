<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 15:03
 */
//车辆管理加油记录表
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\RefuelingrecordModel;//加油
use think\Db;
use think\Loader;

class Refuelingrecord extends Base
{

    /*
     * 获取一条车辆管理加油记录表
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $refuelingrecord = new RefuelingrecordModel();
            $param = input('post.');
            $data = $refuelingrecord->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条车辆管理加油记录表
     */
    public function  refuelingrecordEdit()
    {
        $refuelingrecord = new RefuelingrecordModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))//id为空的时候为新增
            {
                $data = [
//                    'id' => $param['id'],
                    'pid' =>$param['pid'],//车辆管理表的id
                    'kilometre_number' => $param['kilometre_number'],//当前公里数
                    'kilometre_difference' => $param['kilometre_difference'],//本次与上次公里差
                    'refueling_time' => $param['refueling_time'],//本次加油时间
                    'last_refueling_time' => $param['last_refueling_time'],//上次加油时间
                    'refueling_type' => $param['refueling_type'],//加油类型
                    'fuel_quantity' => $param['fuel_quantity'],//加油量(升)
                    'price' => $param['price'],//单价(元)
                    'total' => $param['total'],//合计(元)
                    'kilometer_oil' => $param['kilometer_oil']//百公里耗油
                ];
                $flag = $refuelingrecord->insertRefuelingrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $param['id'],
                    'kilometre_number' => $param['kilometre_number'],//当前公里数
                    'kilometre_difference' => $param['kilometre_difference'],//本次与上次公里差
                    'refueling_time' => $param['refueling_time'],//本次加油时间
                    'last_refueling_time' => $param['last_refueling_time'],//上次加油时间
                    'refueling_type' => $param['refueling_type'],//加油类型
                    'fuel_quantity' => $param['fuel_quantity'],//加油量(升)
                    'price' => $param['price'],//单价(元)
                    'total' => $param['total'],//合计(元)
                    'kilometer_oil' => $param['kilometer_oil']//百公里耗油
                ];
                $flag = $refuelingrecord->editRefuelingrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条车辆管理加油记录表
     */
    public function refuelingrecordDel()
    {
        $refuelingrecord = new RefuelingrecordModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $refuelingrecord->delRefuelingrecord($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}