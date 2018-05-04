<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 10:45
 */

namespace app\quality\model;


use think\exception\PDOException;
use think\Model;

class MaterialfileModel extends Model
{
    protected $name = 'project_test_data';

    public function insertMater($param)
    {
        try{
            $result = $this->allowField(true)->save($param[0]);
            $relevance_id = $this->getLastInsID();
            $new_param = [];
            foreach($param as $k=>$v){
                if($k>0){
                    $new_param[$k] = $v;
                    $new_param[$k]['relevance_id'] = $relevance_id;
                }
            }
            $result = $this->allowField(true)->saveAll($new_param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function editMater($param)
    {
        try{
            $result = $this->allowField(true)->save($param[0], ['id' => $param[0]['id']]);

            $id_arr = $this->where('relevance_id',$param[0]['id'])->column('id');

            foreach($id_arr as $k=>$v){
                $this->allowField(true)->save($param[$k+1], ['id' => $v]);
            }

            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delMater($id)
    {
        try{
            $this->where('id', $id)->whereOr('relevance_id',$id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getAllOne($id)
    {
        $data = $this->where(['id'=>$id])->whereOr(['relevance_id'=>$id])->select();
        return $data;
    }

    public function getOne($id)
    {
        return $this->where(['id'=>$id])->find();
    }

}