<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/15
 * Time: 18:36
 */
//事故档案
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\AccidentfileModel;
use think\Db;
use think\Loader;

class Accidentfile extends Base
{
    /*
     * 获取一条事故报告信息
    */
    public function index()
    {
        return $this->fetch();
    }
    /**
     * 新增一条事故档案记录
     * @return \think\response\Json
     */
    public function accidentfileAdd()
    {
        if(request()->isAjax()){
            $accident = new AccidentfileModel();
            $param = input('post.');
                $data = [
                    'accident_name' => $param['accident_name'],//事故名称
                    'accident_time' => $param['accident_time'],//事故发生时间
                    'accident_place' => $param['accident_place'],//事故发生地点
                    'accident_type' => $param['accident_type'],//事故类型
                    'accident_level' => $param['accident_level'],//事故等级
                    'death_toll' => $param['death_toll'],//死亡人数
                    'injure' => $param['injure'],//重伤人数
                    'light_injure' => $param['light_injure'],//轻伤人数
                    'economic_loss' => $param['economic_loss'],//经济损失
                    'accident_unit' => $param['accident_unit'],//事故责任单位
                    'accident_result' => $param['accident_result'],//事故处理结果
                    'remark' => $param['remark']//备注

                ];
                $flag = $accident->insertAccidentfile($data);

            return json(['code' => $flag['code'], 'data' => $flag, 'msg' => $flag['msg']]);
        }
    }
}

