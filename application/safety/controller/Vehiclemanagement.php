<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 11:32
 */
//车辆管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\VehiclemanagementModel;//车辆管理
use app\safety\model\RepairrecordModel;//维修
use app\safety\model\MaintenancerecordModel;//保养
use app\safety\model\RefuelingrecordModel;//加油
use think\Db;
use think\Loader;

class Vehiclemanagement extends Base
{
    /*
     * 获取一条车辆管理
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $vehiclemanagement = new VehiclemanagementModel();
            $param = input('post.');
            $data = $vehiclemanagement->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条车辆管理信息
     */
    public function  vehiclemanagementEdit()
    {
        $vehiclemanagement = new VehiclemanagementModel();
        $param = input('post.');

        $year_limit = strtotime($param['year_limit']);//年审有效期
        $insurance_limit = strtotime($param['insurance_limit']);//保险有效期
        $now = strtotime("now");

        if($now > $year_limit)
        {
            $vehicle_state = "年审过期";
        }else if($now > $insurance_limit)
        {
            $vehicle_state = "保险过期";
        }else
        {
            $vehicle_state = "正常";
        }

        if(request()->isAjax()){
            if(empty($param['id']))//id为空的时候为新增
            {
                $data = [
//                    'id' => $param['id'],
                    'car_number' => $param['car_number'],//车牌号
                    'fixed_assets_number' => $param['fixed_assets_number'],//固定资产编号
                    'vehicle_category' => $param['vehicle_category'],//车辆类别
                    'year_limit' => $param['year_limit'],//年审有效期
                    'insurance_limit' => $param['insurance_limit'],//保险有效期
                    'vehicle_state' => $vehicle_state,//车辆状态
                    'driver' => $param['driver'],//驾驶员
                    'input_time' => $param['input_time'],//导入时间
                    'date' => date('Y-m-d H:i:s'),
                    'remark' => $param['remark']//备注
                ];
                $flag = $vehiclemanagement->insertVehiclemanagement($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $param['id'],
                    'car_number' => $param['car_number'],//车牌号
                    'fixed_assets_number' => $param['fixed_assets_number'],//固定资产编号
                    'vehicle_category' => $param['vehicle_category'],//车辆类别
                    'year_limit' => $param['year_limit'],//年审有效期
                    'insurance_limit' => $param['insurance_limit'],//保险有效期
                    'vehicle_state' => $param['vehicle_state'],//车辆状态
                    'driver' => $param['driver'],//驾驶员
                    'remark' => $param['remark']//备注
                ];
                $flag = $vehiclemanagement->editVehiclemanagement($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条车辆管理信息
     */
    public function vehiclemanagementDel()
    {
        $vehiclemanagement = new VehiclemanagementModel();
        $repairrecord = new RepairrecordModel();//维修记录
        $maintenancerecord = new MaintenancerecordModel();//保养记录
        $refuelingrecord = new RefuelingrecordModel();//加油记录
        if(request()->isAjax()) {
            $param = input('post.');
//            $data = $vehiclemanagement->getOne($param['id']);
//            $path = $data['path'];
//            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
//            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
//            if(file_exists($path)){
//                unlink($path); //删除文件
//            }
//            if(file_exists($pdf_path)){
//                unlink($pdf_path); //删除生成的预览pdf
//            }
            $flag = $vehiclemanagement->delPersonalequipment($param['id']);

            $repair = $repairrecord->getOne($param['id']);
            if($repair)
            {
                $flag1 = $repairrecord->delPid($param['id']);
            }

            $maintenance = $maintenancerecord->getOne($param['id']);
            if($maintenance)
            {
                $flag2 = $maintenancerecord->delPid($param['id']);
            }

            $refueling = $refuelingrecord->getOne($param['id']);
            if($refueling)
            {
                $flag3 = $refuelingrecord->delPid($param['id']);
            }

            if($flag || $flag1 || $flag2 || $flag3)
            {
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }


        }
    }

    /*
     * 获取车辆管理信息,excel导入时间
     * @return mixed|\think\response\Json
     */
    public function getversion()
    {
        if(request()->isAjax()){
            $vehiclemanagement= new VehiclemanagementModel();
            $data = $vehiclemanagement->getVersion();
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * 车辆管理信息excel表格导入
     * @return array|\think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importExcel()
    {
//        $selfid = input('param.selfid');
//        if(empty($selfid)){
//            return  json(['code' => 1,'data' => '','msg' => '请选择分组']);
//        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/vehiclemanagement');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/vehiclemanagement' . DS . $exclePath;   //上传文件的地址
            // 当文件后缀是xlsx 或者 csv 就会报：the filename xxx is not recognised as an OLE file错误
            $extension = get_extension($file_name);
            if ($extension =='xlsx') {
                $objReader = new \PHPExcel_Reader_Excel2007();
                $obj_PHPExcel = $objReader->load($file_name);
            } else if ($extension =='xls') {
                $objReader = new \PHPExcel_Reader_Excel5();
                $obj_PHPExcel = $objReader->load($file_name);
            } else if ($extension=='csv') {
                $PHPReader = new \PHPExcel_Reader_CSV();
                //默认输入字符集
                $PHPReader->setInputEncoding('GBK');
                //默认的分隔符
                $PHPReader->setDelimiter(',');
                //载入文件
                $obj_PHPExcel = $PHPReader->load($file_name);
            }
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $car_number_index = $fixed_assets_number_index = $vehicle_category_index = $year_limit_index = $insurance_limit_index = $vehicle_state_index = $driver_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '车牌号'){
                    $car_number_index = $k;
                }else if ($str == '固定资产编号'){
                    $fixed_assets_number_index = $k;
                }else if($str == '车辆类别'){
                    $vehicle_category_index = $k;
                }else if($str == '年审有效期'){
                    $year_limit_index = $k;
                }else if($str == '保险有效期'){
                    $insurance_limit_index = $k;
                }else if($str == '车辆状态'){
                    $vehicle_state_index = $k;
                }else if($str == '驾驶员'){
                    $driver_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
//            return json(['1'=> $number_index, '2' => $equip_name_index,'3' =>$model_index,'4' => $equip_num_index,'5' => $manufactur_unit_index,'6' => $date_production_index,'7' => $current_state_index,'8' => $safety_inspection_num_index,'9' => $inspection_unit_index,'10' => $entry_time_index,'11' => $equip_state_index,'12' => $remark_index]);

            if($car_number_index == -1 || $fixed_assets_number_index == -1 || $vehicle_category_index == -1 || $year_limit_index == -1 || $insurance_limit_index == -1 || $vehicle_state_index == -1 || $driver_index == -1  || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){

                    $insertData[$k]['car_number'] = $v[$car_number_index];
                    $insertData[$k]['fixed_assets_number'] = $v[$fixed_assets_number_index];
                    $insertData[$k]['vehicle_category'] = $v[$vehicle_category_index];
                    $insertData[$k]['year_limit'] = $v[$year_limit_index];
                    $insertData[$k]['insurance_limit'] = $v[$insurance_limit_index];
                    $insertData[$k]['vehicle_state'] = $v[$vehicle_state_index];
                    $insertData[$k]['driver'] = $v[$driver_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');

                }
            }
            $success = Db::name('safety_vehicle_management')->insertAll($insertData);
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
        $vehiclemanagement = new VehiclemanagementModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $vehiclemanagement ->getallid();
        }
        $name = '个人防护装备'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $vehiclemanagement->getList($idArr);
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
            ->setCellValue('B1', '车牌号')
            ->setCellValue('C1', '固定资产编号')
            ->setCellValue('D1', '车辆类别')
            ->setCellValue('E1', '年审有效期')
            ->setCellValue('F1', '保险有效期')
            ->setCellValue('G1', '车辆状态')
            ->setCellValue('H1', '驾驶员')
            ->setCellValue('I1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['car_number'])
                ->setCellValue('C'.$key, $v['fixed_assets_number'])
                ->setCellValue('D'.$key, $v['vehicle_category'])
                ->setCellValue('E'.$key, $v['year_limit'])
                ->setCellValue('F'.$key, $v['insurance_limit'])
                ->setCellValue('G'.$key, $v['vehicle_state'])
                ->setCellValue('H'.$key, $v['driver'])
                ->setCellValue('I'.$key, $v['remark']);
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