<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 17:43
 */
//内部设备设施管理,登高工器具
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\BoardinginnerModel;
use think\Db;
use think\Loader;

class Boardinginner extends Base
{
    /*
     * 获取一条登高工器具信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $boarding = new BoardinginnerModel();
            $param = input('post.');
            $data = $boarding->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 编辑一条登高工器具信息
     */
    public function  boardinginnerEdit()
    {
        $boarding = new BoardinginnerModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'tool_name' => $param['tool_name'],//工器具名称
                'type_model' => $param['type_model'],//规格型号
                'number' => $param['number'],//数量
                'batch' => $param['batch'],//批次
                'manufacture' => $param['manufacture'],//生产厂家
                'date_product' => $param['date_product'],//出厂日期
                'check_round' => $param['check_round'],//定检周期
                'first_check_date' => $param['first_check_date'],//首检日期
                'use_position' => $param['use_position'],//使用位置
                'remark' => $param['remark']//备注
            ];
            $flag = $boarding->editBoardinginner($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 删除一条登高工器具信息
     */
    public function boardinginnerDel()
    {
        $boardingequipment = new BoardingequipmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $boardingequipment->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $boardingequipment->delBoardingequipment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}