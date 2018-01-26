<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2018/1/4
 * Time: 1:01
 */

namespace app\quality\model;


use think\Model;

class QCProblemModel extends Model
{
    protected $name = 'qc_problem';

    public function insertQc($param)
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

    public function editQc($param)
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

    public function delQc($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

    public function delProblemByGroupId($groupId){
        $has = $this->where('group_id',$groupId)->value('id');
        if($has){
            $bol = $this->whereIn('group_id',$groupId)->delete();
            if($bol < 1){
                return ['code' => 0, 'data' => '', 'msg' => 'Problem删除失败'];
            }
        }
        return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
    }
}