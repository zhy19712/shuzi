<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 安全风险管理
use app\admin\controller\Base;
use app\safety\model\RiskManageModel;
use think\Db;
use think\Loader;

class Riskmanage extends Base
{
    /**
     * 预览获取一条数据  或者  编辑获取一条数据
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $manage = new RiskManageModel();
            $data = $manage->getOne($param['major_key']);
            return json($data);
        }
        return $this ->fetch();
    }

    /**
     * 新增或者编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function manageAddOrEdit()
    {
        if(request()->isAjax()){
            $manage = new RiskManageModel();
            $param = input('post.');
            if(isset($param['year_filename']) && empty($param['year_name'])){
                $param['year_name'] = $param['year_filename'];
            }
            if(isset($param['quarter_filename']) && empty($param['quarter_name'])){
                $param['quarter_name'] = $param['quarter_filename'];
            }
            if(isset($param['sheet_filename']) && empty($param['sheet_name'])){
                $param['sheet_name'] = $param['sheet_filename'];
            }
            if(isset($param['card_filename']) && empty($param['card_name'])){
                $param['card_name'] = $param['card_filename'];
            }
            if(isset($param['work_filename']) && empty($param['work_name'])){
                $param['work_name'] = $param['work_filename'];
            }
            if(empty($param['major_key'])){
                $flag =$manage->insertManage($param);
            }else{
                $is_exist = $manage->getOne($param['major_key']);
                if(empty($is_exist)){
                    return json(['code' => '-1', 'msg' => '不存在的编号，请刷新当前页面']);
                }
                $flag = $manage->editManage($param);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 当新增时，文件还未保存时，点击删除文件
     * 前台需要给我  path 文件路径 根据路径删除文件
     * 删除上传的文件
     * @return \think\response\Json
     * @author hutao
     */
    public function delAddFile()
    {
        if(request()->isAjax()){
            $path = input('param.path');
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件 培训材料文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            return json(['code' => 1, 'msg' => '删除成功']);
        }
    }

    /**
     * 编辑时 删除文件
     * 前台 只需要给我  主键 和要删除的文件
     * 类别 type 1 年度风险辨识文件 2 季度风险辨识文件3 风险复测单 4风险管控卡 5施工作业票
     * @return \think\response\Json
     * @author hutao
     */
    public function delEditFile()
    {
        if(request()->isAjax()){
            $major_key = input('param.major_key');
            $types= input('param.type'); // type 1 年度风险辨识文件 2 季度风险辨识文件3 风险复测单 4风险管控卡 5施工作业票
            $edu = new RiskManageModel();
            $flag = $edu->removeEditFile($major_key,$types);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function manageDownload()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $major_key = input('param.major_key');
        $type = input('param.type');
        $manage = new RiskManageModel();
        $param = $manage->getOne($major_key);
        $filePath = $fileName = '';
        if($type == '1'){ // type 1 年度风险辨识文件 2 季度风险辨识文件3 风险复测单 4风险管控卡 5施工作业票
            $filePath = $param['year_path'];
            $fileName = $param['year_name'];
        }else if($type == '2'){
            $filePath = $param['quarter_path'];
            $fileName = $param['quarter_name'];
        }else if($type == '3'){
            $filePath = $param['sheet_path'];
            $fileName = $param['sheet_name'];
        }else if($type == '4'){
            $filePath = $param['card_path'];
            $fileName = $param['card_name'];
        }else if($type == '5'){
            $filePath = $param['work_path'];
            $fileName = $param['work_name'];
        }

        // 如果是手动输入的名称，就有可能没有文件后缀
        $extension = get_extension($fileName);
        if(empty($extension)){
            $fileName = $fileName . '.' . substr(strrchr($filePath, '.'), 1);
        }

        if(file_exists($filePath)) {
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
        }else{
            return json(['code' => '-1','msg' => '文件不存在']);
        }
    }

    /**
     *  上传的文件点击预览为pdf文件
     * @return \think\response\Json
     * @author hutao
     */
    public function managePreview()
    {
        $manage = new RiskManageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $manage->getOne($param['major_key']);
            $path = '';
            if($param['type'] == '1'){ // type 1 年度风险辨识文件 2 季度风险辨识文件3 风险复测单 4风险管控卡 5施工作业票
                $path = $data['year_path'];
            }else if($param['type'] == '2'){
                $path = $data['quarter_path'];
            }else if($param['type'] == '3'){
                $path = $data['sheet_path'];
            }else if($param['type'] == '4'){
                $path = $data['card_path'];
            }else if($param['type'] == '5'){
                $path = $data['work_path'];
            }

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
                    $msg = '文不支持的件格式';
                }
                return json(['code' => $code, 'path' => substr($pdf_path,1), 'msg' => $msg]);
            }else{
                return json(['code' => $code,  'path' => substr($pdf_path,1), 'msg' => $msg]);
            }
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function manageDel()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $manage = new RiskManageModel();
            $flag = $manage->delManage($param['major_key']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 导入
     * @return array|\think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @author hutao
     */
    public function importExcel()
    {
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/manage');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/manage' . DS . $exclePath;   //上传文件的地址
            // 当文件后缀是xlsx 或者 csv 就会报：the filename xxx is not recognised as an OLE file错误
            $extension = get_extension($file_name);
            if ($extension == 'xlsx') {
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
            }else{
                return  json(['code' => 1,'data' => '','msg' => '请选择正确的模板文件']);
            }
            if(!is_object($obj_PHPExcel)){
                return  json(['code' => 1,'data' => '','msg' => '请选择正确的模板文件']);
            }
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $segmentv_index = $position_index = $riskname_index = $risk_grade_index = $on_stream_time_index = $completion_time_index = $construction_user_index = -1;
            $supervision_user_index = $remark_index = $c_iphone_index = $s_iphone_index = $job_content_index = $risk_factor_index = $measures_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '标段'){
                    $segmentv_index = $k;
                }else if ($str == '部位'){
                    $position_index = $k;
                }else if ($str == '风险名称'){
                    $riskname_index = $k;
                }else if($str == '风险等级'){
                    $risk_grade_index = $k;
                }else if($str == '开工时间'){
                    $on_stream_time_index = $k;
                }else if($str == '完工时间'){
                    $completion_time_index = $k;
                }else if($str == '施工单位责任人' || $str == '施工方责任人'){
                    $construction_user_index = $k;
                }else if($str == '监理单位责任人' || $str == '监理责任人'){
                    $supervision_user_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }else if($str == '施工联系电话' || $str == '施工单位责任人联系电话' || $str == '施工方责任人联系电话'){
                    $c_iphone_index = $k;
                }else if($str == '监理联系电话' || $str == '监理单位责任人联系电话' || $str == '监理责任人联系电话'){
                    $s_iphone_index = $k;
                }else if($str == '作业内容'){
                    $job_content_index = $k;
                }else if($str == '风险因素'){
                    $risk_factor_index = $k;
                }else if($str == '预控措施'){
                    $measures_index = $k;
                }
            }
            if($segmentv_index == -1 || $position_index == -1 || $riskname_index == -1 || $risk_grade_index == -1 || $on_stream_time_index == -1 || $completion_time_index == -1 || $construction_user_index == -1){
                $json_data['code'] = -1;
                $json_data['info'] = '请检查标题名称';
                return json($json_data);
            }else if($supervision_user_index == -1 || $remark_index == -1 || $c_iphone_index == -1 || $s_iphone_index == -1 || $job_content_index == -1 || $risk_factor_index == -1 || $measures_index == -1){
                $json_data['code'] = -1;
                $json_data['info'] = '请检查标题名称';
                return json($json_data);
            }
            $insertData = [];
            $new_excel_array = delArrayNull($excel_array); // 删除空数据
            foreach($new_excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['segmentv'] = $v[$segmentv_index];
                    $insertData[$k]['position'] = $v[$position_index];
                    $insertData[$k]['riskname'] = $v[$riskname_index];
                    $insertData[$k]['risk_grade'] = $v[$risk_grade_index];
                    $insertData[$k]['on_stream_time'] = $v[$on_stream_time_index];
                    $insertData[$k]['completion_time'] = $v[$completion_time_index];
                    $insertData[$k]['construction_user'] = $v[$construction_user_index];
                    $insertData[$k]['supervision_user'] = $v[$supervision_user_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['c_iphone'] = $v[$c_iphone_index];
                    $insertData[$k]['s_iphone'] = $v[$s_iphone_index];
                    $insertData[$k]['job_content'] = $v[$job_content_index];
                    $insertData[$k]['risk_factor'] = $v[$risk_factor_index];
                    $insertData[$k]['measures'] = $v[$measures_index];
                    // 非表格数据
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['import_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['owner'] = session('username');
                    $insertData[$k]['filename'] = $file->getInfo('name');
                    $insertData[$k]['path'] = './uploads/safety/import/manage/' . str_replace("\\","/",$exclePath);
                }
            }
            $success = Db::name('safety_riskmanage')->insertAll($insertData);
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
     * @author hutao
     */
    public function exportExcel()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $majorKeyArr = input('majorKeyArr/a');
        if(count($majorKeyArr) == 0){
            return json(['code' => -1 ,'msg' => '请选择需要下载的编号']);
        }
        $name = '安全风险管理 - '.date('Y-m-d H:i:s'); // 导出的文件名
        $manage = new RiskManageModel();
        $list = $manage->getList($majorKeyArr);
        if(count($list) == 0){
            return json(['code' => -1 ,'msg' => '数据为空']);
        }
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '标段')
            ->setCellValue('C1', '部位')
            ->setCellValue('D1', '风险名称')
            ->setCellValue('E1', '风险等级')
            ->setCellValue('F1', '开工时间')
            ->setCellValue('G1', '完工时间')
            ->setCellValue('H1', '施工单位责任人')
            ->setCellValue('I1', '监理单位责任人')
            ->setCellValue('J1', '备注')
            ->setCellValue('K1', '施工联系电话')
            ->setCellValue('L1', '监理联系电话')
            ->setCellValue('M1', '作业内容')
            ->setCellValue('N1', '风险因素')
            ->setCellValue('O1', '预控措施');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['major_key'])
                ->setCellValue('B'.$key, $v['segmentv'])
                ->setCellValue('C'.$key, $v['position'])
                ->setCellValue('D'.$key, $v['riskname'])
                ->setCellValue('E'.$key, $v['risk_grade'])
                ->setCellValue('F'.$key, $v['on_stream_time'])
                ->setCellValue('G'.$key, $v['completion_time'])
                ->setCellValue('H'.$key, $v['construction_user'])
                ->setCellValue('I'.$key, $v['supervision_user'])
                ->setCellValue('J'.$key, $v['remark'])
                ->setCellValue('K'.$key, $v['c_iphone'])
                ->setCellValue('L'.$key, $v['s_iphone'])
                ->setCellValue('M'.$key, $v['job_content'])
                ->setCellValue('N'.$key, $v['risk_factor'])
                ->setCellValue('O'.$key, $v['measures']);
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
     * @author hutao
     */
    public function exportExcelTemplete()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $newName = '安全风险管理模板'; // 导出的文件名
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '标段')
            ->setCellValue('C1', '部位')
            ->setCellValue('D1', '风险名称')
            ->setCellValue('E1', '风险等级')
            ->setCellValue('F1', '开工时间')
            ->setCellValue('G1', '完工时间')
            ->setCellValue('H1', '施工单位责任人')
            ->setCellValue('I1', '监理单位责任人')
            ->setCellValue('J1', '备注')
            ->setCellValue('K1', '施工联系电话')
            ->setCellValue('L1', '监理联系电话')
            ->setCellValue('M1', '作业内容')
            ->setCellValue('N1', '风险因素')
            ->setCellValue('O1', '预控措施');
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

    /**
     * 查看历史版本
     * @return \think\response\Json
     * @author hutao
     */
    public function getHistory()
    {
        if(request()->isAjax()){
            $edu = new RiskManageModel();
            $history = $edu->getImportTime();
            return json($history);
        }
    }

}