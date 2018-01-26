<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/28
 * Time: 14:35
 */

namespace app\quality\model;


use think\Model;

class ProjectAttachmentModel extends Model
{
    protected $name = 'project_attachment';
    /**
     * 插入
     */
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

    /**
     * 编辑信息
     */
    public function editAttachment($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['uid' => $param['uid']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '文件编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [删除附件]
     * @return [type] [description]
     */
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
                return ['code' => 0, 'data' => '', 'msg' => '删除附件失败'];
            }
        }
        return ['code' => 1, 'data' => '', 'msg' => '删除附件成功'];
    }




    /**
     * 根据id获取信息
     * @param $uid
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 根据pid和uid删除attachment
     * 此关联数据不是必须的，如果不存在也返回true
     * @param $pid
     * @param $uid
     * @return array
     */
    public function delAttachmentByPidUid($pid,$uid){
        // 是否包含attachment数据信息
        $has = $this->where(['pid'=>$pid,'uid'=>$uid])->value('id');
        // 包含执行删除
        if($has){
            $bol = $this->delAttachment($has);
            if($bol < 1){
                return ['code' => 0, 'data' => '', 'msg' => 'attachment删除失败'];
            }
        }
        return ['code' => 1, 'data' => '', 'msg' => 'attachment删除成功'];
    }
}