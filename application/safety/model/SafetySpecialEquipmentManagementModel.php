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
use think\Db;

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
                $id = $this->getLastInsID();
                return ['code' => 1, 'id' => $id,'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 添加新的特种设备管理文件上传图片
    */
    public function insertSpecialEquipmentManagePic($param)
    {
        try{
            $result = Db::name('safety_special_equipment_manage_pic')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1,'data' => '', 'msg' => '添加成功'];
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
     * 编辑特种设备管理文件上传图片
    */
    public function editSpecialEquipmentManagementPic($param)
    {
        try{
            //查询之前上传的图片是否存在
            $state = Db::name('safety_special_equipment_manage_pic')->where('pid',$param['pid'])->find();
            if($state)
            {
                //编辑之前先把原来的图片删除

                $result_pic = Db::name('safety_special_equipment_manage_pic')->where('pid', $param['pid'])->delete();
                if($result_pic)
                {
                    $path = $state['path'];
                    if(file_exists($path)){
                        unlink($path); //删除已经上传的图片
                    }
                }

            }

            $result =  Db::name('safety_special_equipment_manage_pic')->allowField(true)->save($param, ['uid' => $param['id']]);

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
            //删除上传图片表中的图片记录
            Db::name('safety_special_equipment_manage_pic')->where('uid',$id)->delete();
            $picture_data = Db::name('safety_special_equipment_manage_pic')->field("path")->where('uid',$id)->select();
            foreach((array)$picture_data as $k=>$v)
            {
                unlink($v['path']); //删除文件
            }
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条特种设备管理文件,获取特种设备文件对应的图片
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
        $picture = Db::name('safety_special_equipment_manage_pic') ->field("uid,picture_name,path")->where('uid',$id)->select();

        $equip = $this->where('id', $id)->find();

        return ['equip' => $equip, 'picture' => $picture];


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