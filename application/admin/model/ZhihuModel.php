<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/27
 * Time: 11:32
 */

namespace app\admin\model;


use think\Model;

class ZhihuModel extends Model
{
    protected $name = 'project_zhihu';
    /**
     * 插入
     */
    public function insertZhihu($param)
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
    public function editZhihu($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['uid' => $param['uid']]);
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
     * 根据uid获取信息
     * @param $uid
     */
    public function getOne($uid)
    {
        return $this->where('uid', $uid)->find();
    }

    //get counts by id
    public function getNum($uid)
    {
        return $this->where('uid',$uid)->count();
    }
    public function getQualifiedNum($uid)
    {
        $where['uid'] = $uid;
        $where['pass'] = '是';
        return $this->where($where)->count();
    }
    public function getGoodNum($uid)
    {
        $where['uid'] = $uid;
        $where['quality_level'] = '优良';
        return $this->where($where)->count();
    }
}