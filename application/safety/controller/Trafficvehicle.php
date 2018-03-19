<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 17:47
 */
//交通车辆
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\TrafficvehicleModel;

class Trafficvehicle extends Base
{

    /*
     * 获取一条交通车辆信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $traffic = new TrafficvehicleModel();
            $param = input('post.');
            $data = $traffic->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 编辑一条交通车辆信息
     */
    public function  trafficEdit()
    {
        $traffic = new TrafficvehicleModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'number_pass' => $param['number_pass'],//通行证编号
                'subord_unit' => $param['subord_unit'],//所属单位
                'car_number' => $param['car_number'],//车牌号/自编号
                'vehicle_type' => $param['vehicle_type'],//车辆类型
                'year_limit' => $param['year_limit'],//年审有效期
                'insurance_limit' => $param['insurance_limit'],//保险有效期
                'charage_person' => $param['charage_person'],//负责人/驾驶员
                'entry_time' => $param['entry_time'],//进场时间
                'car_state' => $param['car_state'],//车辆状态
                'remark' => $param['remark']
            ];
            $flag = $traffic->editTrafficvehicle($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 删除一条交通车辆信息
     */
    public function trafficDel()
    {
        $traffic = new TrafficvehicleModel();
        if(request()->isAjax()) {
            $param = input('post.');
//            $data = $traffic->getOne($param['id']);
//            $path = $data['path'];
//            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
//            if(file_exists($path)){
//                unlink($path); //删除文件
//            }
//            if(file_exists($pdf_path)){
//                unlink($pdf_path); //删除生成的预览pdf
//            }
            $flag = $traffic->delTrafficvehicle($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 新增一条交通车辆信息
     */
    public function  trafficInsert()
    {
        $traffic = new TrafficvehicleModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
//                'id' => $param['id'],
                'number_pass' => $param['number_pass'],//通行证编号
                'subord_unit' => $param['subord_unit'],//所属单位
                'car_number' => $param['car_number'],//车牌号/自编号
                'vehicle_type' => $param['vehicle_type'],//车辆类型
                'year_limit' => $param['year_limit'],//年审有效期
                'insurance_limit' => $param['insurance_limit'],//保险有效期
                'charage_person' => $param['charage_person'],//负责人/驾驶员
                'entry_time' => $param['entry_time'],//进场时间
                'car_state' => $param['car_state'],//车辆状态
                'remark' => $param['remark']
            ];
            $flag = $traffic->insertTrafficvehicle($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

}