<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 14:11
 */
//作业安全，交叉作业管理
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class CrossoperationModel extends Model
{
    protected $name = 'safety_cross_operation';


    /*
     * 添加新的交叉作业管理文件
    */
    public function insertCrossoperation($param)
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
    * 编辑交叉作业管理文件
    */
    public function editCrossoperation($param)
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
    * 删除交叉作业管理文件
    */
    public function delCrossoperation($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /*
    * 获取一条交叉作业管理管理信息
    */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
}