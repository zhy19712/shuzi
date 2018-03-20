<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 11:00
 */
//车辆管理加油记录表
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class RefuelingrecordModel extends Model
{
    protected $name = 'safety_refueling_record';

    /*
     * 添加新的车辆管理加油记录表
     */
    public function insertRefuelingrecord($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 编辑车辆管理加油记录表
     */
    public function editRefuelingrecord($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 删除车辆管理加油记录表
     */
    public function delRefuelingrecord($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 批量删除同一pid的车辆管理加油记录表
     */
    public function delPid($id)
    {
        try{
            $this->where('pid', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条车辆管理加油记录表
     */
    public function getOne($id)
    {

        return $this->where('id', $id)->find();

    }
}