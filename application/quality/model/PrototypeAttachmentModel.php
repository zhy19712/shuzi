<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2018/1/7
 * Time: 0:12
 */

namespace app\quality\model;


use think\Model;

class PrototypeAttachmentModel extends Model
{
    protected $name = 'prototype_attachment';

    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

    public function insertAttachment($param)
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

    public function editAttachment($param)
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

    public function delAttachment($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '删除附件成功'];
    }

    public function getInfo($group_id, $table_name1,$table_name2,$table_name3)
    {
        $data = array();
        $where1['group_id'] = $group_id;
        $where1['table_name'] = $table_name1;

        $where2['group_id'] = $group_id;
        $where2['table_name'] = $table_name2;

        $where3['group_id'] = $group_id;
        $where3['table_name'] = $table_name3;

        array_push($data,$this->where($where1)->find());
        array_push($data,$this->where($where2)->find());
        array_push($data,$this->where($where3)->find());
        return $data;
    }

    public function getImageId($group_id, $table_name)
    {
        $where['group_id'] = $group_id;
        $where['table_name'] = $table_name;
        return $this->where($where)->find();
    }
}