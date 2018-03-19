<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 人员教育培训
use app\admin\controller\Base;
use app\admin\model\ContractModel;
use app\safety\model\EdupeopleModel;
use think\Db;
use think\Loader;

class Edupeople extends Base
{
    /**
     * 编辑获取一条数据
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $edu = new EdupeopleModel();
            $data = $edu->getOne($param['id']);
            return json($data);
        }
        return $this ->fetch();
    }

    /**
     * 主要负责人和安全管理人员
     * 新增或者修改
     * @return \think\response\Json
     * @author hutao
     */
    public function eduAdd()
    {
        if(request()->isAjax()){
            $edu = new EdupeopleModel();
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
            $edu = new EdupeopleModel();
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/edupeople');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/edupeople' . DS . $exclePath;   //上传文件的地址
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
            $edu_name_index = $job_index = $certificate_name_index = $certificate_number_index = $availability_date_index = $vld_index = $training_mode_index = -1;
            $training_time_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '姓名'){
                    $edu_name_index = $k;
                }else if ($str == '职务'){
                    $job_index = $k;
                }else if ($str == '证书名称'){
                    $certificate_name_index = $k;
                }else if($str == '证书编号'){
                    $certificate_number_index = $k;
                }else if($str == '生效日期'){
                    $availability_date_index = $k;
                }else if($str == '有效期至'){
                    $vld_index = $k;
                }else if($str == '培训方式'){
                    $training_mode_index = $k;
                }else if($str == '培训时间'){
                    $training_time_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($edu_name_index == -1 || $job_index == -1 || $certificate_name_index == -1||
                $certificate_number_index == -1 || $availability_date_index == -1 || $vld_index == -1 ||
                $training_mode_index == -1 || $training_time_index == -1 || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '请检查标题名称';
                return json($json_data);
            }
            $insertData = [];
            $new_excel_array = delArrayNull($excel_array); // 删除空数据
            foreach($new_excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['edu_name'] = $v[$edu_name_index];
                    $insertData[$k]['job'] = $v[$job_index];
                    $insertData[$k]['certificate_name'] = $v[$certificate_name_index];
                    $insertData[$k]['certificate_number'] = $v[$certificate_number_index];
                    $insertData[$k]['availability_date'] = $v[$availability_date_index];
                    $insertData[$k]['vld'] = $v[$vld_index];
                    $insertData[$k]['training_mode'] = $v[$training_mode_index];
                    $insertData[$k]['training_time'] = $v[$training_time_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    // 非表格数据
                    $param['owner'] = session('username');
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['import_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['pid'] = $pid;
                    $insertData[$k]['zid'] = $zid;
                    $insertData[$k]['filename'] = $file->getInfo('name');
                    $insertData[$k]['path'] = './uploads/safety/import/edupeople/' . str_replace("\\","/",$exclePath);
                }
            }
            $success = Db::name('safety_edupeople')->insertAll($insertData);
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
            return json(['code' => -1 ,'msg' => '请选择需要下载的编号']);
        }
        $name = '人员教育培训 - '.date('Y-m-d H:i:s'); // 导出的文件名
        $edu = new EdupeopleModel();
        $list = $edu->getList($idArr);
        if(count($list) == 0){
            return json(['code' => -1 ,'msg' => '数据为空']);
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
            ->setCellValue('C1', '职务')
            ->setCellValue('D1', '证书名称')
            ->setCellValue('E1', '证书编号')
            ->setCellValue('F1', '生效日期')
            ->setCellValue('G1', '有效期至')
            ->setCellValue('H1', '培训方式')
            ->setCellValue('I1', '培训时间')
            ->setCellValue('J1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['edu_name'])
                ->setCellValue('C'.$key, $v['job'])
                ->setCellValue('D'.$key, $v['certificate_name'])
                ->setCellValue('E'.$key, $v['certificate_number'])
                ->setCellValue('F'.$key, $v['availability_date'])
                ->setCellValue('G'.$key, $v['vld'])
                ->setCellValue('H'.$key, $v['training_mode'])
                ->setCellValue('I'.$key, $v['training_time'])
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
     * @author hutao
     */
    public function exportExcelTemplete()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $newName = '人员教育培训模板'; // 导出的文件名
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
            ->setCellValue('C1', '职务')
            ->setCellValue('D1', '证书名称')
            ->setCellValue('E1', '证书编号')
            ->setCellValue('F1', '生效日期')
            ->setCellValue('G1', '有效期至')
            ->setCellValue('H1', '培训方式')
            ->setCellValue('I1', '培训时间')
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

    /**
     * 查看历史版本
     * @return \think\response\Json
     * @author hutao
     */
    public function getHistory()
    {
        if(request()->isAjax()){
            $edu = new EdupeopleModel();
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

    /**
     * 获取右侧  当前路径: 获取路径
     * @return \think\response\Json
     * @author hutao
     */
    public function getParents()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $pid = $param['pid'];
            $id = $param['id'];
            if($pid == 0 && $id == 1 ){
                $path = "主要负责人和安全管理人员";
            }else if($pid == 0 && $id == 2 ){
                $path = "从业人员";
            }else if($pid == 0 && $id == 3 ){
                $path = "外来人员";
            }else{
                $node = new ContractModel();
                $id = $id - 10;
                $data = $node->getOneContract($id);
                if($pid == 1){
                    $path = "主要负责人和安全管理人员 >> " . $data['biaoduan_name'];
                }else if($pid == 2){
                    $path = "从业人员 >> " . $data['biaoduan_name'];
                }else{
                    $path = "外来人员 >> " . $data['biaoduan_name'];
                }
            }
            return json(['path' => $path, 'msg' => "success", 'code'=>1]);
        }
    }

}