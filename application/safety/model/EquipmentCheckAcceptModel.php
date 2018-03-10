<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/9
 * Time: 14:26
 */
//设备设施验收
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class EquipmentCheckAcceptModel extends Model
{
    protected $name = 'safety_accept_equipment';

    /*
     * 添加新的设备设施验收文件
    */
    public function insertEquipmentCheckAccept($param)
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
     * 编辑设备设施验收文件
    */
    public function editEquipmentCheckAccept($param)
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
     * 删除设备设施验收文件
    */
    public function delEquipmentCheckAccept($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条安全文化建设文件
    */
    public function getOne($id)
    {

        return $this->where('id', $id)->find();

    }

}