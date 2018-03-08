<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 14:11
 */
//安全生产信息化建设
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;
class SafetyResponsibilityinfoModel extends Model
{
    protected $name = 'safety_responsibilityinfo';
    /*
     * 添加新的安全生产信息化建设文件
    */
    public function insertSafetyResponsibilityinfo($param)
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
    * 编辑安全生产信息化建设文件
    */
    public function editSafetyResponsibilityinfo($param)
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
    * 删除安全生产信息化建设文件
    */
    public function delSafetyResponsibilityinfo($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /*
    * 获取一条安全生产信息化建设文件信息
    */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
}