<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/12
 * Time: 15:09
 */
//特种设备管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\SafetySpecialEquipmentManagementModel;

class Specialequipmentmanagement extends Base
{
    /*
     * 获取一条特种设备管理信息
    */
    public function index()
    {
        if(request()->isAjax()){
            $equipment= new SafetySpecialEquipmentManagementModel();
            $param = input('post.');
            $data = $equipment->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     *编辑一条特种设备管理信息
    */
    public function  equipmentEdit()
    {
        $equipment = new SafetySpecialEquipmentManagementModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],//特种设备表id
                'equip_name' => $param['equip_name'],//设备名称
                'model' => $param['model'],//型号
                'equip_num' => $param['equip_num'],//设备编号
                'manufactur_unit' => $param['manufactur_unit'],//制造单位
                'date_production' => $param['date_production'],//出厂日期
                'current_state' => $param['current_state'],//当前状态
                'equip_manage_department' => $param['equip_manage_department'],//设备管理部门
                'safety_machinery_time' => $param['safety_machinery_time'],//安全准用证挂牌时间
                'safety_inspection_num' => $param['safety_inspection_num'],//安全检验合格证书编号
                'inspection_unit' => $param['inspection_unit'],//检验单位
                'safety_inspecte_certificate_time' => $param['safety_inspecte_certificate_time'],//安全检验合格证书有效截止日期
                'equipmen_overhaul' => $param['equipmen_overhaul'],//设备是否经过大修
                'date_overhaul' => $param['date_overhaul'],//大修日期
                'entry_time' => $param['entry_time'],//进场时间
                'equip_state' => $param['equip_state'],//设备状态
                'remark' => $param['remark']//备注

            ];
            $flag = $equipment->editSpecialEquipmentManagement($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     *删除一条特种设备管理信息
    */
    public function equipmentDel()
    {
        $equipment = new SafetySpecialEquipmentManagementModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $equipment->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $equipment->delSpecialEquipmentManagement($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     *预览一条特种设备管理信息
   */
    public function equipmentPreview()
    {
        $equipment = new SafetySpecialEquipmentManagementModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $equipment->getOne($param['id']);
            $path = $data['path'];
            $extension = strtolower(get_extension(substr($path,1)));
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(!file_exists($pdf_path)){
                if($extension === 'doc' || $extension === 'docx' || $extension === 'txt'){
                    doc_to_pdf($path);
                }else if($extension === 'xls' || $extension === 'xlsx'){
                    excel_to_pdf($path);
                }else if($extension === 'ppt' || $extension === 'pptx'){
                    ppt_to_pdf($path);
                }else if($extension === 'pdf'){
                    $pdf_path = $path;
                }else{
                    $code = 0;
                    $msg = '不支持的文件格式';
                }
                return json(['code' => $code, 'path' => substr($pdf_path,1), 'msg' => $msg]);
            }else{
                return json(['code' => $code,  'path' => substr($pdf_path,1), 'msg' => $msg]);
            }
        }
    }

    /*
     * 获取特种设备的版本信息
    */
    public function getversion()
    {
        if(request()->isAjax()){
            $equipment= new SafetySpecialEquipmentManagementModel();
            $data = $equipment->getVersion();
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

}