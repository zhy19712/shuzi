<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/7
 * Time: 14:01
 */

namespace app\safety\model;


use think\exception\PDOException;
use think\Model;

class RevisionrecordModel extends Model
{
    protected $name = 'safety_record';

    public function insertRecord($param)
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

    public function editRecord($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['major_key' => $param['major_key']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delRecord($major_key)
    {
        try{
            $this->where('major_key', $major_key)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOne($major_key)
    {
        return $this->where('major_key', $major_key)->find();
    }

    public function getList($majorKeyArr)
    {
        $list = [];
        foreach ($majorKeyArr as $v){
            $list[] = $this->find($v);
        }
        return $list;
    }

    public function getYears()
    {
        return $this->where('improt_time is not null')->group('years')->column('years');
    }

}