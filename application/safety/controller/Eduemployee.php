<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 从业人员
use app\admin\controller\Base;
use app\admin\model\ContractModel;
use app\safety\model\EduemployeeModel;
use think\Db;
use think\Loader;

class Eduemployee extends Base
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
            $edu = new EduemployeeModel();
            $data = $edu->getOne($param['id']);
            return json($data);
        }
        return $this ->fetch();
    }

    /**
     * 新增或者修改
     * @return \think\response\Json
     * @author hutao
     */
    public function eduAdd()
    {
        if(request()->isAjax()){
            $edu = new EduemployeeModel();
            $param = input('post.');
            if(empty($param['id'])){
                if(isset($param['id'])){
                    unset($param['id']); // 避免提交的id是0 或者 空 的时候的赋值
                }
                $param['owner'] = session('username');
                $param['years'] = date("Y");
                $flag = $edu->insertEdu($param);
            }else{
                $flag = $edu->editEdu($param);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function eduDel()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $edu = new EduemployeeModel();
            $flag = $edu->delEdu($param['id']);
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
        $pid = input('param.pid');
        $zid = input('param.zid');
        if(empty($pid) || empty($zid)){
            return  json(['code' => 1,'data' => '','msg' => '请选择分组']);
        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/eduemployee');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/eduemployee' . DS . $exclePath;   //上传文件的地址
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
            }else{
                return  json(['code' => 1,'data' => '','msg' => '请选择正确的模板文件']);
            }
            if(!is_object($obj_PHPExcel)){
                return  json(['code' => 1,'data' => '','msg' => '请选择正确的模板文件']);
            }
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $edu_name_index = $sex_index = $id_on_index = $job_index = $situation_index = $iphone_index = $approach_tiem_index = -1;
            $content_index = $edu_time_index = $exam_performance_index = $exit_time_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '姓名'){
                    $edu_name_index = $k;
                }else if ($str == '性别'){
                    $sex_index = $k;
                }else if ($str == '身份证号'){
                    $id_on_index = $k;
                }else if($str == '职务/工种'){
                    $job_index = $k;
                }else if($str == '持证情况'){
                    $situation_index = $k;
                }else if($str == '联系方式'){
                    $iphone_index = $k;
                }else if($str == '进场时间'){
                    $approach_tiem_index = $k;
                }else if($str == '培训内容'){
                    $content_index = $k;
                }else if($str == '培训时间'){
                    $edu_time_index = $k;
                }else if($str == '考试成绩'){
                    $exam_performance_index = $k;
                }else if($str == '退场时间'){
                    $exit_time_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($edu_name_index == -1 || $sex_index == -1 || $id_on_index == -1 || $job_index == -1 || $situation_index == -1 || $iphone_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '请检查标题名称';
                return json($json_data);
            }else if ($approach_tiem_index == -1 || $content_index == -1 || $edu_time_index == -1 || $exam_performance_index == -1 || $exit_time_index == -1 || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '请检查标题名称';
                return json($json_data);
            }
            $insertData = [];
            $new_excel_array = delArrayNull($excel_array); // 删除空数据
            foreach($new_excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['edu_name'] = $v[$edu_name_index];
                    $insertData[$k]['sex'] = $v[$sex_index];
                    $insertData[$k]['id_on'] = $v[$id_on_index];
                    $insertData[$k]['job'] = $v[$job_index];
                    $insertData[$k]['situation'] = $v[$situation_index];
                    $insertData[$k]['iphone'] = $v[$iphone_index];
                    $insertData[$k]['approach_tiem'] = $v[$approach_tiem_index];
                    $insertData[$k]['content'] = $v[$content_index];
                    $insertData[$k]['edu_time'] = $v[$edu_time_index];
                    $insertData[$k]['exam_performance'] = $v[$exam_performance_index];
                    $insertData[$k]['exit_time'] = $v[$exit_time_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    // 非表格数据
                    $insertData[$k]['pid'] = $pid;
                    $insertData[$k]['zid'] = $zid;
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['improt_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['owner'] = session('username');
                    $insertData[$k]['filename'] = $file->getInfo('name');
                    $insertData[$k]['path'] = './uploads/safety/import/eduemployee/' . str_replace("\\","/",$exclePath);
                }
            }
            $success = Db::name('safety_eduemployee')->insertAll($insertData);
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
        $idArr = input('id/a');
        if(count($idArr) == 0){
            return json(['code' => 1 ,'msg' => '请选择需要下载的编号']);
        }
        $name = '从业人员 - '.date('Y-m-d H:i:s'); // 导出的文件名
        $edu = new EduemployeeModel();
        $list = $edu->getList($idArr);
        if(count($list) == 0){
            return json(['code' => 1 ,'msg' => '数据为空']);
        }
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
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '性别')
            ->setCellValue('D1', '身份证号')
            ->setCellValue('E1', '职务/工种')
            ->setCellValue('F1', '持证情况')
            ->setCellValue('G1', '联系方式')
            ->setCellValue('H1', '进场时间')
            ->setCellValue('I1', '培训内容')
            ->setCellValue('J1', '培训时间')
            ->setCellValue('K1', '考试成绩')
            ->setCellValue('L1', '退场时间')
            ->setCellValue('M1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['edu_name'])
                ->setCellValue('C'.$key, $v['sex'])
                ->setCellValue('D'.$key, $v['id_on'])
                ->setCellValue('E'.$key, $v['job'])
                ->setCellValue('F'.$key, $v['situation'])
                ->setCellValue('G'.$key, $v['iphone'])
                ->setCellValue('H'.$key, $v['approach_tiem'])
                ->setCellValue('I'.$key, $v['content'])
                ->setCellValue('J'.$key, $v['edu_time'])
                ->setCellValue('K'.$key, $v['exam_performance'])
                ->setCellValue('L'.$key, $v['exit_time'])
                ->setCellValue('M'.$key, $v['remark']);
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
        $newName = '从业人员模板'; // 导出的文件名
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
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '性别')
            ->setCellValue('D1', '身份证号')
            ->setCellValue('E1', '职务/工种')
            ->setCellValue('F1', '持证情况')
            ->setCellValue('G1', '联系方式')
            ->setCellValue('H1', '进场时间')
            ->setCellValue('I1', '培训内容')
            ->setCellValue('J1', '培训时间')
            ->setCellValue('K1', '考试成绩')
            ->setCellValue('L1', '退场时间')
            ->setCellValue('M1', '备注');
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
            $edu = new EduemployeeModel();
            $history = $edu->getImportTime();
            return json($history);
        }
    }

    /**
     * 初始化左侧节点树
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author hutao
     */
    public function getSegment()
    {
        if(request()->isAjax()){
            $con = new ContractModel();
            $data = $con->getBiaoduanName(3);
            return json($data);
        }
    }

}