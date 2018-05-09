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

class StandardDeviationModel extends Model
{
    protected $name = 'project_standard_deviation';
    //自动写入创建、更新时间 insertGetId和update方法中无效，只能用于save方法
    protected $autoWriteTimestamp = true;

    public function insertMater($param)
    {
        try{
            // genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $this->where(['genre'=>$param['genre'],'gid'=>$param['gid'],'unit_type'=>$param['unit_type'],'type'=>$param['type']])->delete();
            $result = $this->allowField(true)->saveAll($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}