<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 11:01
 */
//应急物资
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EmergencysuppliesModel;

class Emergencysupplies extends Base
{
    /*
    * 获取一条应急物资信息
    */
    public function index()
    {
        if(request()->isAjax()){
            $emergencysupplies = new EmergencysuppliesModel();
            $param = input('post.');
            $data = $emergencysupplies->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条应急物资信息
     */
    public function emergencysuppliesEdit()
    {
        $emergencysupplies = new EmergencysuppliesModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['bid']))
            {
                $data = [
                    'material_name' => $param['material_name'],//设备物资名称
                    'material_model' => $param['material_model'],//规格型号
                    'material_company' => $param['material_company'],//单位
                    'material_number' => $param['material_number'],//数量
                    'material_situation' => $param['material_situation'],//完好情况
                    'material_location' => $param['material_location'],//存放地点
                    'remark' => $param['remark']//备注
                ];
                $flag = $emergencysupplies->insertEmergencysupplies($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else
            {
                $data = [
                    'id' => $param['bid'],//应急物资自增id
                    'material_name' => $param['material_name'],//设备物资名称
                    'material_model' => $param['material_model'],//规格型号
                    'material_company' => $param['material_company'],//单位
                    'material_number' => $param['material_number'],//数量
                    'material_situation' => $param['material_situation'],//完好情况
                    'material_location' => $param['material_location'],//存放地点
                    'remark' => $param['remark']//备注
                ];
                $flag = $emergencysupplies->editEmergencysupplies($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条应急物资信息
     */
    public function emergencysuppliesDel()
    {
        $emergencysupplies = new EmergencysuppliesModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $emergencysupplies->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $emergencysupplies->delEmergencysupplies($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}