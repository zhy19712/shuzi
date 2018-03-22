<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/19
 * Time: 16:28
 */
//个人防护装备
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\PersonalequipmentModel;
use think\Db;
use think\Loader;

class Personalequipment extends Base
{
    /*
     * 获取一条个人防护装备信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $personal = new PersonalequipmentModel();
            $param = input('post.');
            $data = $personal->getOne($param['id']);
            //文件名、图片名、文件路径，图片路径不为空时进行拆解
            if(!empty($data['filename']))
            {
                $data['filename'] = explode("☆",$data['filename']);//拆解拼接的文件、图片名
            }else
            {
                $data['filename'] = array();//为空时返回一个空数组
            }

            if(!empty($data['path']))
            {
                $data['path'] = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            }else
            {
                $data['path'] = array();//为空时返回一个空数组
            }

            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条个人防护装备信息
     */
    public function  personalEdit()
    {
        $personal = new PersonalequipmentModel();
        $param = input('post.');

        $pathImgName = input('post.pathImgName/a');//获取post传过来的多个文件、图片的名字，包含在一个一维数组中。
        $pathImgArr = input('post.pathImgArr/a');//获取post传过来的多个文件、图片的路径，包含在一个一维数组中。
        $pathImgDel = input('post.pathImgDel/a');//获取post传过来要删除的多个文件、图片的路径，包含在一个一维数组中。

        //判断文件名、图片名、路径是否为空，为空的时候不拼接

        if(!empty($pathImgName))
        {
            $pathImgName = implode("☆",$pathImgName);//上传所有文件图片的拼接名
        }
        if(!empty($pathImgArr))
        {
            $pathImgArr = implode("☆",$pathImgArr);//上传所有文件、图片拼接路径

        }

        //循环删除文件、图片
        foreach((array)$pathImgDel as $v)
        {
            if(file_exists($v)){
                unlink($v); //删除上传的文件、路径
            }
        }

        if(request()->isAjax()){
            if(empty($param['id']))
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'tool_name' => $param['tool_name'],//工器具名称
                    'type_model' => $param['type_model'],//规格型号
                    'number' => $param['number'],//数量
                    'batch' => $param['batch'],//批次
                    'manufacture' => $param['manufacture'],//生产厂家
                    'date_product' => $param['date_product'],//出厂日期
                    'check_round' => $param['check_round'],//定检周期
                    'first_check_date' => $param['first_check_date'],//首检日期
                    'use_position' => $param['use_position'],//使用位置
                    'input_time' => $param['input_time'],

                    'filename' => $pathImgName,//拼接文件名、图片名

                    'path' => $pathImgArr,//拼接文件路径、图片路径
                    'date' => date("Y-m-d H:i:s"),//添加时间
                    'remark' => $param['remark']//备注
                ];

                $flag = $personal->insertPersonalequipment($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else
            {
                $data = [
                    'id' => $param['id'],
//                    'selfid' => $param['selfid'],//区别类别
                    'tool_name' => $param['tool_name'],//工器具名称
                    'type_model' => $param['type_model'],//规格型号
                    'number' => $param['number'],//数量
                    'batch' => $param['batch'],//批次
                    'manufacture' => $param['manufacture'],//生产厂家
                    'date_product' => $param['date_product'],//出厂日期
                    'check_round' => $param['check_round'],//定检周期
                    'first_check_date' => $param['first_check_date'],//首检日期
                    'use_position' => $param['use_position'],//使用位置
//                    'input_time' => $param['input_time'],

                    'filename' => $pathImgName,//拼接文件名、图片名

                    'path' => $pathImgArr,//拼接文件路径、图片路径
//                    'date' => date("Y-m-d H:i:s"),//添加时间
                    'remark' => $param['remark']//备注
                ];
            }

            $flag = $personal->editPersonalequipment($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 删除一条个人防护装备信息
     */
    public function personalDel()
    {
        $personal = new PersonalequipmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $personal->getOne($param['id']);

            if(!empty($data['path']))
            {
                $path = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            }

            foreach ((array)$path as $v)
            {
                unlink($v); //删除文件、图片
            }
            $flag = $personal->delPersonalequipment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 获取个人防护设备信息,excel导入时间
     * @return mixed|\think\response\Json
     */
    public function getversion()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $selfid = $param['selfid'];
            $personal= new PersonalequipmentModel();
            $data = $personal->getVersion($selfid);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * 个人防护装备excel表格导入
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/personalequipment');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/personalequipment' . DS . $exclePath;   //上传文件的地址
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
            $tool_name_index = $type_model_index = $number_index = $batch_index = $manufacture_index = $date_product_index = $check_round_index = $first_check_date_index = $use_position_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '工器具名称'){
                    $tool_name_index = $k;
                }else if ($str == '规格型号'){
                    $type_model_index = $k;
                }else if($str == '数量'){
                    $number_index = $k;
                }else if($str == '批次'){
                    $batch_index = $k;
                }else if($str == '生产厂家'){
                    $manufacture_index = $k;
                }else if($str == '出厂日期'){
                    $date_product_index = $k;
                }else if($str == '定检周期'){
                    $check_round_index = $k;
                }else if($str == '首检日期'){
                    $first_check_date_index = $k;
                }else if($str == '使用位置'){
                    $use_position_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
//            return json(['1'=> $number_index, '2' => $equip_name_index,'3' =>$model_index,'4' => $equip_num_index,'5' => $manufactur_unit_index,'6' => $date_production_index,'7' => $current_state_index,'8' => $safety_inspection_num_index,'9' => $inspection_unit_index,'10' => $entry_time_index,'11' => $equip_state_index,'12' => $remark_index]);

            if($tool_name_index == -1 || $type_model_index == -1 || $number_index == -1 || $batch_index == -1 || $manufacture_index == -1 || $date_product_index == -1 || $check_round_index == -1 || $first_check_date_index == -1 || $use_position_index == -1 || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){

                    $insertData[$k]['tool_name'] = $v[$tool_name_index];
                    $insertData[$k]['type_model'] = $v[$type_model_index];
                    $insertData[$k]['number'] = $v[$number_index];
                    $insertData[$k]['batch'] = $v[$batch_index];
                    $insertData[$k]['manufacture'] = $v[$manufacture_index];
                    $insertData[$k]['date_product'] = $v[$date_product_index];
                    $insertData[$k]['check_round'] = $v[$check_round_index];
                    $insertData[$k]['first_check_date'] = $v[$first_check_date_index];
                    $insertData[$k]['use_position'] = $v[$use_position_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['selfid'] = $selfid;

                }
            }
            $success = Db::name('safety_personal_equipment')->insertAll($insertData);
            if($success !== false){
                return  json(['code' => 1,'data' => '','msg' => '导入成功']);
            }else{
                return json(['code' => -1,'data' => '','msg' => '导入失败']);
            }
        }
    }

    /*
     *
     * 根据条件筛选选中的条数
     */
    public function getcount()
    {
        $personalequipment = new PersonalequipmentModel();

        $where = "";//定义一个空数组

        if(request()->isAjax()) {
            $param = input('post.');

            $selfid = $param['selfid'];//类别id
            $year = $param['year'];//年份
            $history_version = $param['history_version'];

            if(!empty($selfid))
            {
                $where .= "selfid =  '$selfid' ";
            }

            if(!empty($year))
            {
                $where .= " and date like '%" .$year. "%' ";
            }

            if(!empty($history_version))
            {
                $where .= " and input_time like '%" .$history_version. "%' ";
            }


            $flag = $personalequipment->getallcount($where);

            return json(['code' => 1, 'data' => $flag, 'msg' => $flag['msg']]);

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
        $personalequipment = new PersonalequipmentModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $personalequipment ->getallid();
        }
        $name = '个人防护装备'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $personalequipment->getList($idArr);
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        /*右键属性所显示的信息*/
//        $objPHPExcel->getProperties()->setCreator("zxf")  //作者
//        ->setLastModifiedBy("zxf")  //最后一次保存者
//        ->setTitle('数据EXCEL导出')  //标题
//        ->setSubject('数据EXCEL导出') //主题
//        ->setDescription('导出数据')  //描述
//        ->setKeywords("excel")   //标记
//        ->setCategory("result file");  //类别
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '工器具名称')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '数量')
            ->setCellValue('E1', '批次')
            ->setCellValue('F1', '生产厂家')
            ->setCellValue('G1', '出厂日期')
            ->setCellValue('H1', '定检周期')
            ->setCellValue('I1', '首检日期')
            ->setCellValue('J1', '使用位置')
            ->setCellValue('K1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['tool_name'])
                ->setCellValue('C'.$key, $v['type_model'])
                ->setCellValue('D'.$key, $v['number'])
                ->setCellValue('E'.$key, $v['batch'])
                ->setCellValue('F'.$key, $v['manufacture'])
                ->setCellValue('G'.$key, $v['date_product'])
                ->setCellValue('H'.$key, $v['check_round'])
                ->setCellValue('I'.$key, $v['first_check_date'])
                ->setCellValue('J'.$key, $v['use_position'])
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
        $newName = '个人防护装备 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        /*右键属性所显示的信息*/
//        $objPHPExcel->getProperties()->setCreator("zxf")  //作者
//        ->setLastModifiedBy("zxf")  //最后一次保存者
//        ->setTitle('数据EXCEL导出')  //标题
//        ->setSubject('数据EXCEL导出') //主题
//        ->setDescription('导出数据')  //描述
//        ->setKeywords("excel")   //标记
//        ->setCategory("result file");  //类别
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '工器具名称')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '数量')
            ->setCellValue('E1', '批次')
            ->setCellValue('F1', '生产厂家')
            ->setCellValue('G1', '出厂日期')
            ->setCellValue('H1', '定检周期')
            ->setCellValue('I1', '首检日期')
            ->setCellValue('J1', '使用位置')
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