<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 11:00
 */
//车辆管理
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class VehiclemanagementModel extends Model
{
    protected $name = 'safety_vehicle_management';

    /*
     * 添加新的车辆管理信息
     */
    public function insertVehiclemanagement($param)
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
     * 编辑车辆管理信息
     */
    public function editVehiclemanagement($param)
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
     * 删除车辆管理信息
     */
    public function delVehiclemanagement($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条车辆管理信息
     */
    public function getOne($id)
    {

        return $this->where('id', $id)->find();

    }

    /*
     * 批量导出时候的数组处理
     */
    public  function getList($idArr)
    {
        $data = [];
        foreach($idArr as $v){
            $data[] = $this->getOne($v);
        }
        return $data;
    }

    /*
     * 查看所有的id值
     */
    public  function getallid()
    {
        return $this->group('id')->column('id');
    }

    /*
     * 获取登高工器具文件的版本日期,excel的导入日期
     */
    public function getVersion()
    {
        return $this->group('input_time')->column('input_time');
    }
}