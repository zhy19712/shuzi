<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 12:58
 */

namespace app\admin\model;


use think\Model;

class QCAttachmentModel extends Model
{
    protected $name = 'qc_attachment';

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
                return ['code' => 1, 'data' => '', 'msg' => '文件编辑成功'];
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
        $where['group_id'] = $group_id;
        $where['table_name'] = $table_name1;
        $where['table_name'] = $table_name2;
        $where['table_name'] = $table_name3;
        $where['_logic'] = 'or';

        return $this->where($where)->SELECT();
    }
}