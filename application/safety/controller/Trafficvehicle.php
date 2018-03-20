<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 17:47
 */
//交通车辆
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\TrafficvehicleModel;
use think\Db;
use think\Loader;

class Trafficvehicle extends Base
{

    /*
     * 获取一条交通车辆信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $traffic = new TrafficvehicleModel();
            $param = input('post.');
            $data = $traffic->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 编辑一条交通车辆信息
     */
    public function  trafficEdit()
    {
        $traffic = new TrafficvehicleModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'number_pass' => $param['number_pass'],//通行证编号
                'subord_unit' => $param['subord_unit'],//所属单位
                'car_number' => $param['car_number'],//车牌号/自编号
                'vehicle_type' => $param['vehicle_type'],//车辆类型
                'year_limit' => $param['year_limit'],//年审有效期
                'insurance_limit' => $param['insurance_limit'],//保险有效期
                'charage_person' => $param['charage_person'],//负责人/驾驶员
                'entry_time' => $param['entry_time'],//进场时间
                'car_state' => $param['car_state'],//车辆状态
                'remark' => $param['remark']
            ];
            $flag = $traffic->editTrafficvehicle($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 删除一条交通车辆信息
     */
    public function trafficDel()
    {
        $traffic = new TrafficvehicleModel();
        if(request()->isAjax()) {
            $param = input('post.');
//            $data = $traffic->getOne($param['id']);
//            $path = $data['path'];
//            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
//            if(file_exists($path)){
//                unlink($path); //删除文件
//            }
//            if(file_exists($pdf_path)){
//                unlink($pdf_path); //删除生成的预览pdf
//            }
            $flag = $traffic->delTrafficvehicle($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 新增一条交通车辆信息
     */
    public function  trafficInsert()
    {
        $traffic = new TrafficvehicleModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'selfid' => $param['selfid'],//类别id
                'number_pass' => $param['number_pass'],//通行证编号
                'subord_unit' => $param['subord_unit'],//所属单位
                'car_number' => $param['car_number'],//车牌号/自编号
                'vehicle_type' => $param['vehicle_type'],//车辆类型
                'year_limit' => $param['year_limit'],//年审有效期
                'insurance_limit' => $param['insurance_limit'],//保险有效期
                'charage_person' => $param['charage_person'],//负责人/驾驶员
                'entry_time' => $param['entry_time'],//进场时间
                'car_state' => $param['car_state'],//车辆状态
                'date' => date("Y-m-d H:i:s"),
                'input_time' => $param['input_time'],
                'remark' => $param['remark']
            ];
            $flag = $traffic->insertTrafficvehicle($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 获取交通车辆信息,excel导入时间
     * @return mixed|\think\response\Json
     */
    public function getversion()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $selfid = $param['selfid'];
            $traffic= new TrafficvehicleModel();
            $data = $traffic->getVersion($selfid);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }


    /**
     * 交通车辆excel表格导入
     * @return array|\think\response\Json
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/trafficvehicle');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/trafficvehicle' . DS . $exclePath;   //上传文件的地址
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
            $number_pass_index = $subord_unit_index = $car_number_index = $vehicle_type_index = $year_limit_index = $insurance_limit_index = $charage_person_index = $entry_time_index = $car_state_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '通行证编号'){
                    $number_pass_index = $k;
                }else if ($str == '所属单位'){
                    $subord_unit_index = $k;
                }else if($str == '车牌号（自编号）'){
                    $car_number_index = $k;
                }else if($str == '车辆类型'){
                    $vehicle_type_index = $k;
                }else if($str == '年审有效期'){
                    $year_limit_index = $k;
                }else if($str == '保险有效期'){
                    $insurance_limit_index = $k;
                }else if($str == '负责人/驾驶员'){
                    $charage_person_index = $k;
                }else if($str == '进场时间'){
                    $entry_time_index = $k;
                }else if($str == '车辆状态'){
                    $car_state_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
//            return json(['1'=> $number_index, '2' => $equip_name_index,'3' =>$model_index,'4' => $equip_num_index,'5' => $manufactur_unit_index,'6' => $date_production_index,'7' => $current_state_index,'8' => $safety_inspection_num_index,'9' => $inspection_unit_index,'10' => $entry_time_index,'11' => $equip_state_index,'12' => $remark_index]);

            if($number_pass_index == -1 || $subord_unit_index == -1 || $car_number_index == -1 || $vehicle_type_index == -1 || $year_limit_index == -1 || $insurance_limit_index == -1 || $charage_person_index == -1 || $entry_time_index == -1 || $car_state_index == -1 || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){

                    $insertData[$k]['number_pass'] = $v[$number_pass_index];
                    $insertData[$k]['subord_unit'] = $v[$subord_unit_index];
                    $insertData[$k]['car_number'] = $v[$car_number_index];
                    $insertData[$k]['vehicle_type'] = $v[$vehicle_type_index];
                    $insertData[$k]['year_limit'] = $v[$year_limit_index];
                    $insertData[$k]['insurance_limit'] = $v[$insurance_limit_index];
                    $insertData[$k]['charage_person'] = $v[$charage_person_index];
                    $insertData[$k]['entry_time'] = $v[$entry_time_index];
                    $insertData[$k]['car_state'] = $v[$car_state_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['selfid'] = $selfid;

                }
            }
            $success = Db::name('safety_vehicle')->insertAll($insertData);
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
        $traffic = new TrafficvehicleModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $traffic ->getallid();
        }
        $name = '交通车辆'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $traffic->getList($idArr);
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
            ->setCellValue('B1', '通行证编号')
            ->setCellValue('C1', '所属单位')
            ->setCellValue('D1', '车牌号（自编号）')
            ->setCellValue('E1', '车辆类型')
            ->setCellValue('F1', '年审有效期')
            ->setCellValue('G1', '保险有效期')
            ->setCellValue('H1', '负责人/驾驶员')
            ->setCellValue('I1', '进场时间')
            ->setCellValue('J1', '车辆状态')
            ->setCellValue('K1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['number_pass'])
                ->setCellValue('C'.$key, $v['subord_unit'])
                ->setCellValue('D'.$key, $v['car_number'])
                ->setCellValue('E'.$key, $v['vehicle_type'])
                ->setCellValue('F'.$key, $v['year_limit'])
                ->setCellValue('G'.$key, $v['insurance_limit'])
                ->setCellValue('H'.$key, $v['charage_person'])
                ->setCellValue('I'.$key, $v['entry_time'])
                ->setCellValue('J'.$key, $v['car_state'])
                ->setCellValue('K'.$key, $v['remark']);
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

    /**
     * 导出模板
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportExcelTemplete()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $name = input('param.name');
        $newName = '交通车辆 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
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
            ->setCellValue('B1', '通行证编号')
            ->setCellValue('C1', '所属单位')
            ->setCellValue('D1', '车牌号（自编号）')
            ->setCellValue('E1', '车辆类型')
            ->setCellValue('F1', '年审有效期')
            ->setCellValue('G1', '保险有效期')
            ->setCellValue('H1', '负责人/驾驶员')
            ->setCellValue('I1', '进场时间')
            ->setCellValue('J1', '车辆状态')
            ->setCellValue('K1', '备注');
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();  //清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel'); //文件类型
        header('Content-Disposition: attachment;filename="'.$newName.'.xls"'); //文件名
        header('Cache-Control: max-age=0');
        header('Content-Type: text/html; charset=utf-8'); //编码
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel 2003
        $objWriter->save('php://output');
        exit;
    }


}