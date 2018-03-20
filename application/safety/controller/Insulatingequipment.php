<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/19
 * Time: 19:40
 */
//绝缘工器具
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\InsulatingequipmentModel;
use think\Db;
use think\Loader;

class Insulatingequipment extends Base
{
    /*
     * 获取一条绝缘工器具信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $insulatingequipment = new InsulatingequipmentModel();
            $param = input('post.');
            $data = $insulatingequipment->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 编辑一条绝缘工器具信息
     */
    public function  insulatingequipmentEdit()
    {
        $insulatingequipment = new InsulatingequipmentModel();
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
            $flag = $insulatingequipment->editInsulatingequipment($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}