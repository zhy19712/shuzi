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

class EducationModel extends Model
{
    protected $name = 'safety_education';

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

    public function delEdu($major_key)
    {
        try{
            $data = $this->getOne($major_key);
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

            $this->where('major_key', $major_key)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch(PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delEduFile($major_key,$types)
    {
        try{
            $data = $this->getOne($major_key);
            if($types == '1'){
                $path = $data['ma_path'];
                $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
                if(file_exists($path)){
                    unlink($path); //删除文件 培训材料文件
                }
                if(file_exists($pdf_path)){
                    unlink($pdf_path); //删除生成的预览pdf
                }
            }else{
                $path2 = $data['re_path'];
                $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
                if(file_exists($path2)){
                    unlink($path2); //删除文件 培训记录文件
                }
                if(file_exists($pdf_path2)){
                    unlink($pdf_path2); //删除生成的预览pdf
                }
            }
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch(PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOne($major_key)
    {
        return $this->where('major_key', $major_key)->find();
    }

    public  function getList($majorKeyArr)
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