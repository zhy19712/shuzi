<?php
/**
 * Created by PhpStorm.
 * User: zhuangyf
 * Date: 2018/1/8
 * Time: 21:34
 */

namespace app\quality\model;


use think\Model;

class ProcedureListModel extends Model
{
    protected $name = 'procedure_list';

    public function insertProcedureList($param)
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

    public function editProcedureList($param)
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

    public function delProcedureList($id)
    {
        try{
            // 关联删除think_procedure_list_sublist
            $proceSub = new ProcedureListSublistModel();
            $bol = $proceSub->delProceSubByGroupId($id);
            if($bol['code'] == 0){
                return $bol;
            }

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

    public function delProceListByGroupId($groupId){
        $idArr = $this->where('group_id',$groupId)->column('id');
        if(count($idArr) > 0){
            foreach ($idArr as $k=>$v){
                $bol = $this->delProcedureList($v);
                if($bol['code'] == 0){
                    return $bol;
                }
            }
        }
        return ['code' => 1, 'data' => '', 'msg' => 'List删除成功'];
    }
}