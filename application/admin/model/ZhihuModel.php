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
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
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
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
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
        $where['quality_level'] = '合格';
        return $this->where($where)->count();
    }
    public function getGoodNum($uid)
    {
        $where['uid'] = $uid;
        $where['quality_level'] = '优良';
        return $this->where($where)->count();
    }

    /**
     * 删除支护
     * 关联删除与支护有联系的project_zhihu_maogan数据信息
     * @param $uid
     * @return array
     */
    public function delZhihuByUid($uid){
        $idArr = $this->where('uid', $uid)->column('id');
        $maogan = new MaoganModel();
        $has = $maogan->getOne($idArr[0]);
        $delChild = true;
        if($has){
            $delChild = $maogan->whereIn('uid',$idArr)->delete();
        }
        if($delChild){
            $bol = $this->where('uid',$uid)->delete();
            if($bol){
                return ['code' => 1, 'data' => '', 'msg' => '支护删除成功'];
            }
            return ['code' => 0, 'data' => '', 'msg' => '支护删除失败'];
        }
        return ['code' => 0, 'data' => '', 'msg' => '锚杆删除失败'];
    }
}