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

    public function delManage($id)
    {
        try{
            $data = $this->getOne($id);
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
        return $this->where('import_time is not null')->group('import_time')->column('import_time');
    }
}