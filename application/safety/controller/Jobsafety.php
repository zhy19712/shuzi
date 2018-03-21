<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:23
 */
//现场管理->作业安全
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\JobsafetyModel;//树状结构
use app\safety\model\ViolationrecordModel;//反违章记录
use app\safety\model\FiremanagementModel;//消防安全管理
use app\safety\model\ChemistrymanagementModel;//危险化学品管理
use think\Db;
use think\Loader;

class Jobsafety extends Base
{

    /*
     * 作业安全左边的树状结构
     */
    public function index()
    {
        if(request()->isAjax()){
            $node = new JobsafetyModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);
        }
        else
            return $this->fetch();
    }


    /********************************************************反违章记录***************************************************************/

    /*
     * 获取一条反违章记录信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $violationrecord = new ViolationrecordModel();
            $param = input('post.');
            $data = $violationrecord->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条反违章记录信息
     */
    public function  violationrecordEdit()
    {
        $violationrecord = new ViolationrecordModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))//id为空时表示新增
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'full_name' => $param['full_name'],//姓名
                    'violation_situation' => $param['violation_situation'],//违章情况
                    'category_violation' => $param['category_violation'],//违章类别
                    'violation_time' => $param['violation_time'],//违章时间
                    'scoring_situation' => $param['scoring_situation'],//记分情况
                    'economic_punishment' => $param['economic_punishment'],//经济处罚
                    'disposal_results' => $param['disposal_results'],//处置结果
                    'rectify_rectify' => $param['rectify_rectify'],//整改情况
                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $violationrecord->insertViolationrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else
            {
                $data = [
                    'id' => $param['id'],
                    'full_name' => $param['full_name'],//姓名
                    'violation_situation' => $param['violation_situation'],//违章情况
                    'category_violation' => $param['category_violation'],//违章类别
                    'violation_time' => $param['violation_time'],//违章时间
                    'scoring_situation' => $param['scoring_situation'],//记分情况
                    'economic_punishment' => $param['economic_punishment'],//经济处罚
                    'disposal_results' => $param['disposal_results'],//处置结果
                    'rectify_rectify' => $param['rectify_rectify'],//整改情况
//                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $violationrecord->editViolationrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条反违章记录信息
     */
    public function violationrecordDel()
    {
        $violationrecord = new ViolationrecordModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $violationrecord->delViolationrecord($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 获取反违章记录信息,excel导入时间
     * @return mixed|\think\response\Json
     */
    public function getversion()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $selfid = $param['selfid'];
            $violationrecord = new ViolationrecordModel();
            $data = $violationrecord->getVersion($selfid);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * 反违章记录excel表格导入
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/violationrecord');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/violationrecord' . DS . $exclePath;   //上传文件的地址
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
            $full_name_index = $violation_situation_index = $category_violation_index = $violation_time_index = $scoring_situation_index = $economic_punishment_index = $disposal_results_index = $rectify_rectify_index =  -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '姓名'){
                    $full_name_index = $k;
                }else if ($str == '违章情况'){
                    $violation_situation_index = $k;
                }else if($str == '违章类别'){
                    $category_violation_index = $k;
                }else if($str == '违章时间'){
                    $violation_time_index = $k;
                }else if($str == '记分情况'){
                    $scoring_situation_index = $k;
                }else if($str == '经济处罚'){
                    $economic_punishment_index = $k;
                }else if($str == '处置结果'){
                    $disposal_results_index = $k;
                }else if($str == '整改情况'){
                    $rectify_rectify_index = $k;
                }
            }

            if($full_name_index == -1 || $violation_situation_index == -1 || $category_violation_index == -1 || $violation_time_index == -1 || $scoring_situation_index == -1 || $economic_punishment_index == -1 || $disposal_results_index == -1 || $rectify_rectify_index == -1 ){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){

                    $insertData[$k]['full_name'] = $v[$full_name_index];
                    $insertData[$k]['violation_situation'] = $v[$violation_situation_index];
                    $insertData[$k]['category_violation'] = $v[$category_violation_index];
                    $insertData[$k]['violation_time'] = $v[$violation_time_index];
                    $insertData[$k]['scoring_situation'] = $v[$scoring_situation_index];
                    $insertData[$k]['economic_punishment'] = $v[$economic_punishment_index];
                    $insertData[$k]['disposal_results'] = $v[$disposal_results_index];
                    $insertData[$k]['rectify_rectify'] = $v[$rectify_rectify_index];
                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['selfid'] = $selfid;

                }
            }
            $success = Db::name('safety_violation_record')->insertAll($insertData);
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
        $violationrecord = new ViolationrecordModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $violationrecord ->getallid();
        }
        $name = '反违章记录'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $violationrecord->getList($idArr);
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
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '违章情况')
            ->setCellValue('D1', '违章类别')
            ->setCellValue('E1', '违章时间')
            ->setCellValue('F1', '记分情况')
            ->setCellValue('G1', '经济处罚')
            ->setCellValue('H1', '处置结果')
            ->setCellValue('I1', '整改情况');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['full_name'])
                ->setCellValue('C'.$key, $v['violation_situation'])
                ->setCellValue('D'.$key, $v['category_violation'])
                ->setCellValue('E'.$key, $v['violation_time'])
                ->setCellValue('F'.$key, $v['scoring_situation'])
                ->setCellValue('G'.$key, $v['economic_punishment'])
                ->setCellValue('H'.$key, $v['disposal_results'])
                ->setCellValue('I'.$key, $v['rectify_rectify']);
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
        $newName = '反违章记录 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
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
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '违章情况')
            ->setCellValue('D1', '违章类别')
            ->setCellValue('E1', '违章时间')
            ->setCellValue('F1', '记分情况')
            ->setCellValue('G1', '经济处罚')
            ->setCellValue('H1', '处置结果')
            ->setCellValue('I1', '整改情况');
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

    /***************************************************消防安全管理********************************************************************/
    /*
     * 获取一条消防安全管理信息
     */
    public function getfireindex()
    {
        if(request()->isAjax()){
            $firemanagement = new FiremanagementModel();
            $param = input('post.');
            $data = $firemanagement->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条消防安全管理信息
     */
    public function  firemanagementEdit()
    {
        $firemanagement = new FiremanagementModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))//id为空时表示新增
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'type' => $param['type'],//类型
                    'specification_model' => $param['specification_model'],//规格型号
                    'placement_position' => $param['placement_position'],//安放位置
                    'number' => $param['number'],//数量
                    'date_manufacture' => $param['date_manufacture'],//生产日期
                    'date_investment' => $param['date_investment'],//投用日期
                    'next_check_time' => $param['next_check_time'],//下次换检时间
                    'serial_number' => $param['serial_number'],//编号
                    'remark' => $param['remark'],//备注
                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $firemanagement->insertFiremanagement($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else
            {
                $data = [
                    'id' => $param['id'],
//                    'selfid' => $param['selfid'],//区别类别
                    'type' => $param['type'],//类型
                    'specification_model' => $param['specification_model'],//规格型号
                    'placement_position' => $param['placement_position'],//安放位置
                    'number' => $param['number'],//数量
                    'date_manufacture' => $param['date_manufacture'],//生产日期
                    'date_investment' => $param['date_investment'],//投用日期
                    'next_check_time' => $param['next_check_time'],//下次换检时间
                    'serial_number' => $param['serial_number'],//编号
                    'remark' => $param['remark'],//备注
//                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $firemanagement->editFiremanagement($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条消防安全管理信息
     */
    public function firemanagementDel()
    {
        $firemanagement = new FiremanagementModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $firemanagement->delFiremanagement($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 获取消防安全管理信息,excel导入时间
     * @return mixed|\think\response\Json
     */
    public function getfireversion()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $selfid = $param['selfid'];
            $firemanagement = new FiremanagementModel();
            $data = $firemanagement->getVersion($selfid);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * 消防安全管理excel表格导入
     * @return array|\think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function fireimportExcel()
    {
        $selfid = input('param.selfid');
        if(empty($selfid)){
            return  json(['code' => 1,'data' => '','msg' => '请选择分组']);
        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/firemanagement');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/firemanagement' . DS . $exclePath;   //上传文件的地址
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
            $model_type_index = $specification_model_index = $placement_position_index = $number_index = $date_manufacture_index = $date_investment_index = $next_check_time_index = $serial_number_index = $remark_index =  -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '类型'){
                    $model_type_index = $k;
                }else if ($str == '规格型号'){
                    $specification_model_index = $k;
                }else if($str == '安放位置'){
                    $placement_position_index = $k;
                }else if($str == '数量'){
                    $number_index = $k;
                }else if($str == '生产日期'){
                    $date_manufacture_index = $k;
                }else if($str == '投用时间'){
                    $date_investment_index = $k;
                }else if($str == '下次换检时间'){
                    $next_check_time_index = $k;
                }else if($str == '编号'){
                    $serial_number_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            return json(['1'=>$model_type_index,'2'=>$specification_model_index,'3'=>$placement_position_index,'4'=>$number_index,'5'=>$date_manufacture_index,'6'=>$date_investment_index,'7'=>$next_check_time_index,'8'=>$serial_number_index,'9'=>$remark_index]);

            if($model_type_index == -1 || $specification_model_index == -1 || $placement_position_index == -1 || $number_index == -1 || $date_manufacture_index == -1 || $date_investment_index == -1 || $next_check_time_index == -1 || $serial_number_index || $remark_index == -1 ){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对111111111';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){

                    $insertData[$k]['model_type'] = $v[$model_type_index];
                    $insertData[$k]['specification_model'] = $v[$specification_model_index];
                    $insertData[$k]['placement_position'] = $v[$placement_position_index];
                    $insertData[$k]['number'] = $v[$number_index];
                    $insertData[$k]['date_manufacture'] = $v[$date_manufacture_index];
                    $insertData[$k]['date_investment'] = $v[$date_investment_index];
                    $insertData[$k]['next_check_time'] = $v[$next_check_time_index];
                    $insertData[$k]['serial_number'] = $v[$serial_number_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['selfid'] = $selfid;

                }
            }
            $success = Db::name('safety_fire_management')->insertAll($insertData);
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
    public function fireexportExcel()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $firemanagement = new FiremanagementModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $firemanagement ->getallid();
        }
        $name = '消防安全管理'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $firemanagement->getList($idArr);
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
            ->setCellValue('B1', '类型')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '安放位置')
            ->setCellValue('E1', '数量')
            ->setCellValue('F1', '生产日期')
            ->setCellValue('G1', '投用时间')
            ->setCellValue('H1', '下次换检时间')
            ->setCellValue('I1', '编号')
            ->setCellValue('J1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['type'])
                ->setCellValue('C'.$key, $v['specification_model'])
                ->setCellValue('D'.$key, $v['placement_position'])
                ->setCellValue('E'.$key, $v['number'])
                ->setCellValue('F'.$key, $v['date_manufacture'])
                ->setCellValue('G'.$key, $v['date_investment'])
                ->setCellValue('H'.$key, $v['next_check_time'])
                ->setCellValue('I'.$key, $v['serial_number'])
                ->setCellValue('J'.$key, $v['remark']);
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
    public function fireexportExcelTemplete()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $name = input('param.name');
        $newName = '消防安全管理 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
//        /*右键属性所显示的信息*/
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
            ->setCellValue('B1', '类型')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '安放位置')
            ->setCellValue('E1', '数量')
            ->setCellValue('F1', '生产日期')
            ->setCellValue('G1', '投用时间')
            ->setCellValue('H1', '下次换检时间')
            ->setCellValue('I1', '编号')
            ->setCellValue('J1', '备注');
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

    /***************************************************危险化学品管理********************************************************************/

    /*
     * 获取一条危险化学品管理信息
     */
    public function getchemistryindex()
    {
        if(request()->isAjax()){
            $chemistry = new ChemistrymanagementModel();
            $param = input('post.');
            $data = $chemistry->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 编辑一条危险化学品管理信息
     */
    public function chemistryEdit()
    {
        $chemistry = new ChemistrymanagementModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'remark' => $param['remark']
            ];
            $flag = $chemistry->editChemistrymanagement($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 下载一条危险化学品管理信息
     */
    public function chemistryDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $chemistry = new ChemistrymanagementModel();
        $param = $chemistry->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['name'];
        $file = fopen($filePath, "r"); //   打开文件
        //输入文件标签
        $fileName = iconv("utf-8","gb2312",$fileName);
        Header("Content-type:application/octet-stream ");
        Header("Accept-Ranges:bytes ");
        Header("Accept-Length:   " . filesize($filePath));
        Header("Content-Disposition:   attachment;   filename= " . $fileName);

        //   输出文件内容
        echo fread($file, filesize($filePath));
        fclose($file);
        exit;
    }

    /*
     * 删除一条危险化学品管理信息
     */
    public function chemistryDel()
    {
        $chemistry = new ChemistrymanagementModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $chemistry->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $chemistry->delChemistrymanagement($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 预览一条危险化学品管理信息
     */
    public function chemistryPreview()
    {
        $info = new SafetyResponsibilityinfoModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $info->getOne($param['id']);
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

}