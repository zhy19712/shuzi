<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2018/1/7
 * Time: 0:13
 */

namespace app\quality\model;


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
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delAttachment($id)
    {
        $data = $this->getOne($id);
        if($data){
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $bol = $this->where('id', $id)->delete();
            if($bol < 1){
                return ['code' => 0, 'data' => '', 'msg' => '删除失败'];
            }
        }
        return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
    }

    public function delProceAttByGroupId($groupId){
        $idArr = $this->where('group_id',$groupId)->column('id');
        if(count($idArr) > 0){
            foreach ($idArr as $k=>$v) {
                $bol = $this->delAttachment($v);
                if($bol['code'] == 0){
                    return $bol;
                }
            }
        }
        return ['code' => 1, 'data' => '', 'msg' => 'Attachment删除成功'];
    }

}