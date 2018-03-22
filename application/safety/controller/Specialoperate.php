<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/21
 * Time: 17:20
 */
//特种作业人员管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\SpecialoperateModel;
use think\Db;
use think\Loader;

class Specialoperate extends Base
{
    /*
     * 获取一条特种作业人员管理信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $special = new SpecialoperateModel();
            $param = input('post.');
            $data = $special->getOne($param['id']);

            $data['filename'] = explode("☆",$data['filename']);//拆解拼接的文件、图片名
            $data['path'] = explode("☆",$data['path']);//拆解拼接的文件、图片路径

            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条消防安全管理信息
     */
    public function  specialoperateEdit()
    {
        $special = new SpecialoperateModel();

        $param = input('post.');

        $pathImgName = input('post.pathImgName/a');//获取post传过来的多个文件、图片的名字，包含在一个一维数组中。
        $pathImgArr = input('post.pathImgArr/a');//获取post传过来的多个文件、图片的路径，包含在一个一维数组中。
        $pathImgDel = input('post.pathImgDel/a');//获取post传过来要删除的多个文件、图片的路径，包含在一个一维数组中。

        //循环删除文件、图片
        foreach((array)$pathImgDel as $v)
        {
            if(file_exists($v)){
                unlink($v); //删除上传的文件、路径
            }
        }

        if(request()->isAjax()){

            if(empty($param['id']))//id为空时表示新增
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'name' => $param['name'],//姓名
                    'job_name' => $param['job_name'],//分包单位/作业队
                    'sex' => $param['sex'],//性别
                    'age' => $param['age'],//年龄
                    'special_type_work' => $param['special_type_work'],//特殊工种
                    'job_certificate' => $param['job_certificate'],//职业资格证书名称
                    'card_number' => $param['card_number'],//身份证号
                    'issuing_unit' => $param['issuing_unit'],//发证单位
                    'date_evidence' => $param['date_evidence'],//取证日期
                    'effective_date' => $param['effective_date'],//有效日期
                    'certificate_number' => $param['certificate_number'],//证书编号
                    'advance_retreat_time' => $param['advance_retreat_time'],//进退场时间
                    'document_status' => $param['document_status'],//证件状态

                    'filename' => implode("☆",$pathImgName),//上传所有文件图片的拼接名
                    'path' => implode("☆",$pathImgArr),//上传所有文件、图片拼接路径

                    'remark' => $param['remark'],//备注
                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $special->insertSpecialoperate($data);


                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else {


                $data = [
                    'id' => $param['id'],
//                    'selfid' => $param['selfid'],//区别类别
                    'name' => $param['name'],//姓名
                    'job_name' => $param['job_name'],//分包单位/作业队
                    'sex' => $param['sex'],//性别
                    'age' => $param['age'],//年龄
                    'special_type_work' => $param['special_type_work'],//特殊工种
                    'job_certificate' => $param['job_certificate'],//职业资格证书名称
                    'card_number' => $param['card_number'],//身份证号
                    'issuing_unit' => $param['issuing_unit'],//发证单位
                    'date_evidence' => $param['date_evidence'],//取证日期
                    'effective_date' => $param['effective_date'],//有效日期
                    'certificate_number' => $param['certificate_number'],//证书编号
                    'advance_retreat_time' => $param['advance_retreat_time'],//进退场时间
                    'document_status' => $param['document_status'],//证件状态

                    'filename' => implode("☆",$pathImgName),//上传所有文件图片的拼接名
                    'path' => implode("☆",$pathImgArr),//上传所有文件、图片拼接路径

                    'remark' => $param['remark'],//备注
//                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $special->editSpecialoperate($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条特种作业人员管理信息
     */
    public function delSpecialoperateDel()
    {
        $special = new SpecialoperateModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $special->getOne($param['id']);
            $path = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            foreach ((array)$path as $v)
            {
                unlink($v); //删除文件、图片
            }
            $flag = $special->delSpecialoperate($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 获取特种作业人员管理,excel导入时间
     * @return mixed|\think\response\Json
     */
    public function getversion()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $selfid = $param['selfid'];
            $special= new SpecialoperateModel();
            $data = $special->getVersion($selfid);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * 特种作业人员管理excel表格导入
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/specialoperate');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/specialoperate' . DS . $exclePath;   //上传文件的地址
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
            $job_name_index = $name_index = $special_type_work_index = $job_certificate_index = $date_evidence_index = $effective_date_index = $document_status_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '分包单位/作业队'){
                    $job_name_index = $k;
                }else if ($str == '姓名'){
                    $name_index = $k;
                }else if($str == '特殊工种'){
                    $special_type_work_index = $k;
                }else if($str == '职业资格证书名称'){
                    $job_certificate_index = $k;
                }else if($str == '取证日期'){
                    $date_evidence_index = $k;
                }else if($str == '有效日期'){
                    $effective_date_index = $k;
                }else if($str == '证件状态'){
                    $document_status_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
//            return json(['1'=> $number_index, '2' => $equip_name_index,'3' =>$model_index,'4' => $equip_num_index,'5' => $manufactur_unit_index,'6' => $date_production_index,'7' => $current_state_index,'8' => $safety_inspection_num_index,'9' => $inspection_unit_index,'10' => $entry_time_index,'11' => $equip_state_index,'12' => $remark_index]);

            if($job_name_index == -1 || $name_index == -1 || $special_type_work_index == -1 || $job_certificate_index == -1 || $date_evidence_index == -1 || $effective_date_index == -1 || $document_status_index == -1 || $remark_index == -1 ){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){

                    $insertData[$k]['job_name'] = $v[$job_name_index];
                    $insertData[$k]['name'] = $v[$name_index];
                    $insertData[$k]['special_type_work'] = $v[$special_type_work_index];
                    $insertData[$k]['job_certificate'] = $v[$job_certificate_index];
                    $insertData[$k]['date_evidence'] = $v[$date_evidence_index];
                    $insertData[$k]['effective_date'] = $v[$effective_date_index];
                    $insertData[$k]['document_status'] = $v[$document_status_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['input_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['selfid'] = $selfid;

                }
            }
            $success = Db::name('safety_special_operate')->insertAll($insertData);
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
        $special = new SpecialoperateModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $special ->getallid();
        }
        $name = '特种作业人员管理'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $special->getList($idArr);
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
            ->setCellValue('B1', '分包单位/作业队')
            ->setCellValue('C1', '姓名')
            ->setCellValue('D1', '特殊工种')
            ->setCellValue('E1', '职业资格证书名称')
            ->setCellValue('F1', '取证日期')
            ->setCellValue('G1', '有效日期')
            ->setCellValue('H1', '证件状态')
            ->setCellValue('I1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['job_name'])
                ->setCellValue('C'.$key, $v['name'])
                ->setCellValue('D'.$key, $v['special_type_work'])
                ->setCellValue('E'.$key, $v['job_certificate'])
                ->setCellValue('F'.$key, $v['date_evidence'])
                ->setCellValue('G'.$key, $v['effective_date'])
                ->setCellValue('H'.$key, $v['document_status'])
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
        $newName = '特种作业人员管理 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
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
            ->setCellValue('B1', '分包单位/作业队')
            ->setCellValue('C1', '姓名')
            ->setCellValue('D1', '特殊工种')
            ->setCellValue('E1', '职业资格证书名称')
            ->setCellValue('F1', '取证日期')
            ->setCellValue('G1', '有效日期')
            ->setCellValue('H1', '证件状态')
            ->setCellValue('I1', '备注');
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