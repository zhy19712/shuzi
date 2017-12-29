<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/29
 * Time: 10:14
 */

namespace app\admin\model;


use think\Model;

class ConstructionModel extends Model
{

    protected $name = 'contract';

    /**
     * 插入
     * @param $param
     */
    public function insertVideo($param)
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
     * @param $param
     */
    public function editVideo($param)
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


    /**
     * [getAllMenu 获取全部信息]
     */
    public function getAll()
    {
        return $this->order('id asc')->select();
    }

    /**
     * 根据id获取信息
     * @param $id
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }




    /**
     * [删除视频]
     */
    public function videoDel()
    {
        $param = input('post.');
        if(request()->isAjax()) {
            $id = $param['id'];
            $attachment = new ProjectAttachmentModel();
            $data = $attachment->getOne($id);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $attachment->delAttachment($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}