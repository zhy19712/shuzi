<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 10:45
 */

namespace app\quality\model;


use think\exception\PDOException;
use think\Model;

class MaterialfileModel extends Model
{
    protected $name = 'project_test_data';
    //自动写入创建、更新时间 insertGetId和update方法中无效，只能用于save方法
    protected $autoWriteTimestamp = true;

    public function insertMater($param)
    {
        try{
            $result = $this->allowField(true)->save($param[0]);
            $relevance_id = $this->getLastInsID();
            $new_param = [];
            foreach($param as $k=>$v){
                if($k>0){
                    $new_param[$k] = $v;
                    $new_param[$k]['relevance_id'] = $relevance_id;
                }
            }
            $result = $this->allowField(true)->saveAll($new_param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function editMater($param)
    {
        try{
            $result = $this->allowField(true)->save($param[0], ['id' => $param[0]['id']]);

            $id_arr = $this->where('relevance_id',$param[0]['id'])->order('id asc')->column('id');

            foreach($id_arr as $k=>$v){
                $this->allowField(true)->save($param[$k+1], ['id' => $v]);
            }

            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delMater($id)
    {
        try{
            $data = $this->getOne($id);
            if(file_exists($data['path'])){
                unlink($data['path']); //删除文件
            }
            $this->where('id', $id)->whereOr('relevance_id',$id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getAllOne($id)
    {
        $data = [];
        $da = $this->where(['id'=>$id])->whereOr(['relevance_id'=>$id])->order('id asc')->select();
        for($i = 0; $i < 20;$i++){
            if($i != 16){
                $data[$i]['entrustment_number'] = $da[$i]['entrustment_number'];
                $data[$i]['report_number'] = $da[$i]['report_number'];
            }
            $data[$i]['approach_detection_time'] = $da[$i]['approach_detection_time'];
            $data[$i]['using_position'] = $da[$i]['using_position']; // 使用部位
            if($i < 15){
                $data[$i]['manufacturer'] = $da[$i]['manufacturer']; // 生产厂家
            }
            if(!in_array($i,[2,14,17,18,19])){
                $data[$i]['specifications'] = $da[$i]['specifications']; // 品种/标号/规格/等级/种类
            }
            if(!in_array($i,[3,4,16,18,19])){
                $data[$i]['lot_number'] = $da[$i]['lot_number']; // 批号/炉号/型号/桩号/母材批号
            }
            if(!in_array($i,[15,17,18,19])){
                $data[$i]['number_of_delegates'] = $da[$i]['number_of_delegates']; // 代表数量/进场数量
            }
            $data[$i]['conclusion'] = $da[$i]['conclusion']; // 结论
            $data[$i]['id'] = $da[$i]['id'];
        }
        $data[0]['filename'] = $da[0]['filename'];
        //  混凝土 ==>委托编号、报告编号、成型日期、破型日期、标段、工程部位、桩号、高程、种类、设计强度等级、龄期(d)、抗压强度(Mpa)、结论
        $data[15]['broken_date'] = $da[15]['broken_date'];
        $data[15]['bids'] = $da[15]['bids'];
        $data[15]['altitude'] = $da[15]['altitude'];
        $data[15]['design_strength_grade'] = $da[15]['design_strength_grade'];
        $data[15]['age'] = $da[15]['age'];
        $data[15]['compression_strength'] = $da[15]['compression_strength'];
        // 钢筋接头==> 检测日期、标段、工程部位、品种规格、钢筋直径、代表数量(个)、结论
        $data[16]['bids'] = $da[16]['bids'];
        $data[16]['bar_diameter'] = $da[16]['bar_diameter'];
        // 止水材料接头==> 委托编号、报告编号、检测日期、标段、工程部位、母材批号、状态、焊接或连接方式、结论
        $data[17]['bids'] = $da[17]['bids'];
        $data[17]['status'] = $da[17]['status'];
        $data[17]['welding'] = $da[17]['welding'];
        // 压实度==> 委托编号、报告编号、检测日期、标段、检测部位、样品名称、结论
        $data[18]['bids'] = $da[18]['bids'];
        $data[18]['kind'] = $da[18]['kind'];
        // 地基承载力==> 序号、委托编号、报告编号、检测日期、标段、检测部位、检测项目、结论
        $data[19]['order_number'] = $da[19]['order_number'];
        $data[19]['bids'] = $da[19]['bids'];
        $data[19]['kind'] = $da[19]['kind'];
        return $data;
    }

    public function getOne($id)
    {
        return $this->where(['id'=>$id])->find();
    }

}