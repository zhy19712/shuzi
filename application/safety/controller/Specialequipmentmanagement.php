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
use think\Db;
use think\Loader;

class Specialequipmentmanagement extends Base
{
    /*
     * 获取一条特种设备管理信息
     * @return mixed|\think\response\Json
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
     * 编辑一条特种设备管理信息
     * @return mixed|\think\response\Json
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
     * 删除一条特种设备管理信息
     * @return mixed|\think\response\Json
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
     * 预览一条特种设备管理信息
     * @return mixed|\think\response\Json
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
     * 获取特种设备的版本信息,excel导入时间
     * @return mixed|\think\response\Json
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

    /**
     * 特种设备管理文件导入
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importExcel()
    {
        $selfid = input('param.selfid');
        if(empty($selfid)){
            return  json(['code' => 1,'data' => '','msg' => '请选择分组']);
        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/specialequipmentmanagement');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/specialequipmentmanagement' . DS . $exclePath;   //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $id_index = $equip_name_index = $model_index = $equip_num_index = $manufactur_unit_index = $date_production_index = $current_state_index
                =  $safety_inspection_num_index =  $inspection_unit_index =  $entry_time_index =  $equip_state_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '序号'){
                    $id_index = $k;
                }else if ($str == '设备名称'){
                    $equip_name_index = $k;
                }else if ($str == '型号'){
                    $model_index = $k;
                }else if($str == '设备编号'){
                    $equip_num_index = $k;
                }else if($str == '制造单位'){
                    $manufactur_unit_index = $k;
                }else if($str == '出厂日期'){
                    $date_production_index = $k;
                }else if($str == '当前状态'){
                    $current_state_index = $k;
                }else if($str == '安全检验合格证书编号'){
                    $safety_inspection_num_index = $k;
                }else if($str == '检验单位'){
                    $inspection_unit_index = $k;
                }else if($str == '进场时间'){
                    $entry_time_index = $k;
                }else if($str == '设备状态'){
                    $equip_state_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($id_index == -1 || $equip_name_index == -1
                || $model_index == -1 || $equip_num_index == -1 ||
                $manufactur_unit_index == -1 || $date_production_index == -1 ||
                $current_state_index == -1 || $safety_inspection_num_index == -1
                || $inspection_unit_index == -1  || $entry_time_index == -1  || $equip_state_index == -1
                || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['id'] = $v[$id_index];
                    $insertData[$k]['equip_name'] = $v[$equip_name_index];
                    $insertData[$k]['model'] = $v[$model_index];
                    $insertData[$k]['equip_num'] = $v[$equip_num_index];
                    $insertData[$k]['manufactur_unit'] = $v[$manufactur_unit_index];
                    $insertData[$k]['date_production'] = $v[$date_production_index];
                    $insertData[$k]['current_state'] = $v[$current_state_index];
                    $insertData[$k]['safety_inspection_num'] = $v[$safety_inspection_num_index];
                    $insertData[$k]['inspection_unit'] = $v[$inspection_unit_index];
                    $insertData[$k]['entry_time'] = $v[$entry_time_index];
                    $insertData[$k]['equip_state'] = $v[$equip_state_index];
                    $insertData[$k]['remark'] = $v[$remark_index];

                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');//excel表格导入时间/版本时间
//                    $insertData[$k]['improt_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['selfid'] = $selfid;
                }
            }
            $success = Db::name('safety_special_equipment_management')->insertAll($insertData);
            if($success !== false){
                return  json(['code' => 1,'data' => '','msg' => '导入成功']);
            }else{
                return json(['code' => -1,'data' => '','msg' => '导入失败']);
            }
        }
    }

    /**
     * 批量导出
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportExcel()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $idArr = input('param.idarr');
        $name = '特种设备管理'.date('Y-m-d H:i:s'); // 导出的文件名
        $equipment = new SafetySpecialEquipmentManagementModel();
        $list = $equipment->getList($idArr);
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        /*右键属性所显示的信息*/
        $objPHPExcel->getProperties()->setCreator("zxf")  //作者
        ->setLastModifiedBy("zxf")  //最后一次保存者
        ->setTitle('数据EXCEL导出')  //标题
        ->setSubject('数据EXCEL导出') //主题
        ->setDescription('导出数据')  //描述
        ->setKeywords("excel")   //标记
        ->setCategory("result file");  //类别
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '设备名称')
            ->setCellValue('C1', '型号')
            ->setCellValue('D1', '设备编号')
            ->setCellValue('E1', '制造单位')
            ->setCellValue('F1', '出厂日期')
            ->setCellValue('G1', '当前状态')
            ->setCellValue('H1', '安全检验合格证书编号')
            ->setCellValue('I1', '检验单位')
            ->setCellValue('J1', '进场时间')
            ->setCellValue('K1', '设备状态')
            ->setCellValue('L1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['equip_name'])
                ->setCellValue('C'.$key, $v['model'])
                ->setCellValue('D'.$key, $v['equip_num'])
                ->setCellValue('E'.$key, $v['manufactur_unit'])
                ->setCellValue('F'.$key, $v['date_production'])
                ->setCellValue('G'.$key, $v['current_state'])
                ->setCellValue('H'.$key, $v['safety_inspection_num'])
                ->setCellValue('I'.$key, $v['inspection_unit'])
                ->setCellValue('I'.$key, $v['inspection_unit'])
                ->setCellValue('I'.$key, $v['inspection_unit'])
                ->setCellValue('I'.$key, $v['inspection_unit']);
        }
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();  //清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel'); //文件类型
        header('Content-Disposition: attachment;filename="'.$name.'.xls"'); //文件名
        header('Cache-Control: max-age=0');
        header('Content-Type: text/html; charset=utf-8'); //编码
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel 2003
        $objWriter->save('php://output');
        exit;
    }

}