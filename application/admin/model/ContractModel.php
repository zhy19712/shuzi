<?php

namespace app\admin\model;
use think\Model;
use think\Db;


class ContractModel extends Model
{
    protected $name = 'contract';

    /**
     * 插入信息
     * @param $param
     */
    public function insertContract($param)
    {
        try{
            $result = $this->validate('ContractValidate')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '合同信息添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * 编辑信息
     * @param $param
     */
    public function editContract($param)
    {
        try{
            $result =  $this->validate('ContractValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '合同信息编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [del_article 删除]
     * @return [type] [description]
     */
    public function delContract($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '合同信息删除成功'];
    }


    /**
     * [getAllMenu 获取全部合同信息]
     */
    public function getAll()
    {
        return $this->order('id asc')->select();
    }

    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneContract($id)
    {
        return $this->where('id', $id)->find();
    }





}