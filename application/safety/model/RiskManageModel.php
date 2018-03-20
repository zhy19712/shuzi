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

class RiskManageModel extends Model
{
    protected $name = 'safety_riskmanage';

    public function insertManage($param)
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

    public function editManage($param)
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

    public function delManage($major_key)
    {
        try{
            $data = $this->getOne($major_key);
            $path[0] = $data['year_path']; // 年度风险辨识文件
            $path[1] = $data['quarter_path']; // 年度风险辨识文件
            $path[2] = $data['sheet_path']; // 年度风险辨识文件
            $path[3]= $data['card_path']; // 风险管控卡
            $path[4] = $data['work_path']; // 施工作业票
            foreach($path as $v){
                $pdf_path = './uploads/temp/' . basename($v) . '.pdf';
                if(file_exists($v)){
                    unlink($v); //删除文件
                }
                if(file_exists($pdf_path)){
                    unlink($pdf_path); //删除生成的预览pdf
                }
            }

            $import_path = $data['path']; // 导入的文件
            if(file_exists($import_path)){
                unlink($import_path); //  删除文件
            }

            $this->where('major_key', $major_key)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch(PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function removeEditFile($major_key,$types)
    {
        try{
            $data = $this->getOne($major_key);
            $edit_data['major_key'] = $major_key;
            // type 1 年度风险辨识文件 2 季度风险辨识文件3 风险复测单 4风险管控卡 5施工作业票
            if($types == '1'){
                $path = $data['year_path'];
                $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
                if(file_exists($path)){
                    unlink($path); //删除文件 年度风险辨识文件
                }
                if(file_exists($pdf_path)){
                    unlink($pdf_path); //删除生成的预览pdf
                }
                $edit_data['year_name'] = '';
                $edit_data['year_filename'] = '';
                $edit_data['year_path'] = '';
            }else if($types == '2'){
                $path2 = $data['quarter_path'];
                $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
                if(file_exists($path2)){
                    unlink($path2); //删除文件 季度风险辨识文件3
                }
                if(file_exists($pdf_path2)){
                    unlink($pdf_path2); //删除生成的预览pdf
                }
                $edit_data['quarter_name'] = '';
                $edit_data['quarter_filename'] = '';
                $edit_data['quarter_path'] = '';
            }else if($types == '3'){
                $path2 = $data['sheet_path'];
                $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
                if(file_exists($path2)){
                    unlink($path2); //删除文件 风险复测单
                }
                if(file_exists($pdf_path2)){
                    unlink($pdf_path2); //删除生成的预览pdf
                }
                $edit_data['sheet_name'] = '';
                $edit_data['sheet_filename'] = '';
                $edit_data['sheet_path'] = '';
            }else if($types == '4'){
                $path2 = $data['card_path'];
                $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
                if(file_exists($path2)){
                    unlink($path2); //删除文件 4风险管控卡
                }
                if(file_exists($pdf_path2)){
                    unlink($pdf_path2); //删除生成的预览pdf
                }
                $edit_data['card_name'] = '';
                $edit_data['card_filename'] = '';
                $edit_data['card_path'] = '';
            }else if($types == '5'){
                $path2 = $data['work_path'];
                $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
                if(file_exists($path2)){
                    unlink($path2); //删除文件 5施工作业票
                }
                if(file_exists($pdf_path2)){
                    unlink($pdf_path2); //删除生成的预览pdf
                }
                $edit_data['work_name'] = '';
                $edit_data['work_filename'] = '';
                $edit_data['work_path'] = '';
            }

            // 文件删除后，修改数据库字段值
            $this->editManage($edit_data);

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