<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/12
 * Time: 14:48
 */
//特种设备管理
namespace app\safety\model;

use think\exception\PDOException;
use think\Model;

class SafetySpecialEquipmentManagementModel extends Model
{
    protected $name = 'safety_special_equipment_management';

    /*
     * 添加新的特种设备管理文件
    */
    public function insertSpecialEquipmentManagement($param)
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
     * 编辑特种设备管理文件
    */
    public function editSpecialEquipmentManagement($param)
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
     * 删除特种设备管理文件
    */
    public function delSpecialEquipmentManagement($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条特种设备管理文件
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

    /*
     * 特种设备批量导出的文件列表id
    */
    public function getList($idArr)
    {
        $data = [];
        foreach($idArr as $v){
            $data[] = $this->getOne($v);
        }
        return $data;
    }

    /*
     * 获取特种设备管理文件的版本日期,excel的导入日期
    */
    public function getVersion($param)
    {
//        return $this->field('input_time')->order('id desc')->select();
        return $this->where('selfid',$param)->group('input_time')->column('input_time');
    }


}