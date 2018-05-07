<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/27
 * Time: 11:31
 */

namespace app\admin\model;


use think\exception\PDOException;
use think\Model;

class MaoganModel extends Model
{
    protected $name = 'project_zhihu_maogan';
    /**
     * 插入
     */
    public function insertMaogan($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '节点添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑信息
     */
    public function editMaogan($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['maoganid']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '节点信息编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [锚杆信息删除]
     */
    public function delMaogan($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '锚杆信息删除成功'];
    }


    /**
     * 根据uid获取信息
     * @param $uid
     */
    public function getOne($uid)
    {
        return $this->where('id', $uid)->find();
    }
}