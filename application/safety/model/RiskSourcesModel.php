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

class RiskSourcesModel extends Model
{
    protected $name = 'safety_risksources';

    public function insertRiskSources($param)
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

    public function editRiskSources($param)
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

    public function delRiskSources($id)
    {
        try{
            $data = $this->getOne($id);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); // 删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); // 删除生成的预览pdf
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
}