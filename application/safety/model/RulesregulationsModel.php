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

class RulesregulationsModel extends Model
{
    protected $name = 'safety_rules';

    public function insertRules($param)
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

    public function editRules($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['major_key' => $param['major_key']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delRules($major_key)
    {
        try{
            $data = $this->getOne($major_key);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); // 删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }

            $import_path = $data['import_path']; // 导入的文件
            if(file_exists($import_path)){
                unlink($import_path); //删除文件
            }

            $this->where('major_key', $major_key)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOne($major_key)
    {
        return $this->where('major_key', $major_key)->find();
    }

    public function delRulesByGroupId($groupId)
    {
        $flag = [];
        $idArr = $this->where('group_id',$groupId)->column('id');
        if(count($idArr) == 0){
            return ['code' => 1, 'data' => '', 'msg' => '不包含Rules文件'];
        }
        foreach($idArr as $k=>$v){
            $flag = $this->delRules($v);
            if($flag['code'] == 0){
                break;
            }
        }
        return ['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']];
    }

    public function getList($majorKeyArr)
    {
        $data = [];
        foreach($majorKeyArr as $v){
            $data[] = $this->getOne($v);
        }
        return $data;
    }

    public function getImportTime()
    {
        return $this->where('import_time is not null')->group('import_time')->column('import_time');
    }

}