<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/14
 * Time: 17:27
 */
//监理部职业健康管理

namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class JobhealthManageModel extends Model
{
    protected $name = 'safety_health_manage';


    /*
     * 获取一条监理部职业健康管理

     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

    /*
     * 插入监理部职业健康管理信息
     */
    public function insertJobhealthManage($param)
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

    /*
     * 编辑监理部职业健康管理信息
     */
    public function editJobhealthManage($param)
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

    /*
     * 删除监理部职业健康管理信息
     */
    public function delJobhealthManage($id)
    {
        try{
            $data = $this->getOne($id);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}
