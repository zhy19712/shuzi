<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2018/1/7
 * Time: 0:13
 */

namespace app\admin\model;


use think\Model;

class ProcedureAttachmentModel extends Model
{
    protected $name = 'procedure_attachment';

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
                return ['code' => 1, 'data' => '', 'msg' => 'success'];
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
                return ['code' => 1, 'data' => '', 'msg' => 'success'];
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

}