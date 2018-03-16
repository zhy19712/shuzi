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

class RiskModel extends Model
{
    protected $name = 'safety_risk';

    /**
     * 发现人
     */
    public function fouder(){
        return $this->hasOne('User','founder_id');
    }

    /**
     * 责任人
     */
    public function workduty()
    {
        return $this->hasOne('User','workduty_id');
    }

    /**
     * 验收人
     */
    public function acceptor()
    {
        return $this->hasOne('User','acceptor_id');
    }
//    /**
//     * 标段
//     */
//    public function section('User','section_id');
    public function insertEdu($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch(PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function editEdu($param)
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

    public function delEdu($id)
    {
        try{
            $data = $this->getOne($id);
            $path = $data['ma_path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件 培训材料文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $path2 = $data['re_path'];
            $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
            if(file_exists($path2)){
                unlink($path2); //删除文件 培训记录文件
            }
            if(file_exists($pdf_path2)){
                unlink($pdf_path2); //删除生成的预览pdf
            }
            $import_path = $data['path'];
            if(file_exists($import_path)){
                unlink($path); //删除文件 导入的文件
            }

            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch(PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

    public  function getList($idArr)
    {
        $data = [];
        foreach($idArr as $v){
            $data[] = $this->getOne($v);
        }
        return $data;
    }

    public function getYears()
    {
        return $this->where('improt_time is not null')->group('improt_time')->column('improt_time');
    }
}