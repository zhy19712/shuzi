<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 13:38
 */
//应急演练方案
namespace app\safety\model;

use think\exception\PDOException;
use think\Model;

class EmergencyschemeModel extends Model
{
    protected $name = 'safety_emergency_rehearsal_scheme';

    /*
     * 添加新的应急演练方案文件
     */
    public function insertEmergencyrehearsalscheme($param)
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
     * 编辑应急演练方案文件
     */
    public function editEmergencyrehearsalscheme($param)
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
     * 删除应急演练方案文件
     */
    public function delEmergencyrehearsalscheme($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /*
     * 获取一条应急演练方案文件
     */
     /**
      * @param $id
      * @return array|false|\PDOStatement|string|Model
      * @throws \think\db\exception\DataNotFoundException
      * @throws \think\db\exception\ModelNotFoundException
      * @throws \think\exception\DbException
      */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
}