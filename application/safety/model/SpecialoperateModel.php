<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 14:11
 */
//作业安全，特种作业人员管理
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class SpecialoperateModel extends Model
{
    protected $name = 'safety_special_operate';


    /*
     * 添加新的特种作业人员管理文件
    */
    public function insertSpecialoperate($param)
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
    * 编辑特种作业人员管理文件
    */
    public function editSpecialoperate($param)
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
    * 删除特种作业人员管理文件
    */
    public function delSpecialoperate($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /*
    * 获取一条特种作业人员管理信息
    */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
}