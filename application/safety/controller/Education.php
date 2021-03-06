<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 专题教育培训
use app\admin\controller\Base;
use app\admin\model\ContractModel;
use app\safety\model\EducationModel;
use think\Db;
use think\Loader;

class Education extends Base
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
            $edu = new EducationModel();
            $data = $edu->getOne($param['major_key']);
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
            $edu = new EducationModel();
            $param = input('post.');
            if(empty($param['major_key'])){
                if(isset($param['major_key'])){
                    unset($param['major_key']); // 避免提交的major_key是0 或者 空 的时候的赋值
                }
                $param['owner'] = session('username');
                $param['years'] = date("Y");
                $flag = $edu->insertEdu($param);
            }else{
                $is_exist = $edu->getOne($param['major_key']);
                if(empty($is_exist)){
                    return json(['code' => '-1', 'msg' => '不存在的编号，请刷新当前页面']);
                }
                // 解决名称为空时，默认还等于原来的文件名称，这样下载和预览时候名称不为空
                $param['material_name'] = empty($param['material_name']) ? $is_exist['ma_filename'] : $param['material_name'];
                $param['record_name'] = empty($param['record_name']) ? $is_exist['re_filename'] : $param['record_name'];
                $flag = $edu->editEdu($param);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function eduDownload()
    {
        $major_key = input('param.major_key');
        $type = input('param.types');
        $edu = new EducationModel();
        $param = $edu->getOne($major_key);
        if($type == '1'){ // type 1 表示的是 培训材料文件 2 表示培训记录文件
            $filePath = $param['ma_path'];
            $fileName = $param['material_name'];
        }else{
            $filePath = $param['re_path'];
            $fileName = $param['record_name'];
        }

        if(!file_exists($filePath)){
            return json(['code' => '-1','msg' => '文件不存在']);
        }else if(request()->isAjax()){
            return json(['code' => 1]);
        }else {
            // 如果是手动输入的名称，就有可能没有文件后缀
            $extension = get_extension($fileName);
            if(empty($extension)){
                $fileName = $fileName . '.' . substr(strrchr($filePath, '.'), 1);
            }
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
    }

    /**
     * 预览
     * @return \think\response\Json
     * @author hutao
     */
    public function eduPreview()
    {
        $edu = new EducationModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $edu->getOne($param['major_key']);
            if($param['type'] == '1'){ // type 1 表示的是 培训材料文件 2 表示培训记录文件
                $path = $data['ma_path'];
            }else{
                $path = $data['re_path'];
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
                    $msg = '不支持的文件格式';
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
    public function eduDel()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $edu = new EducationModel();
            $flag = $edu->delEdu($param['major_key']);
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
     * 前台 只需要给我  主键 和要删除的文件 类别 types 1 培训材料文件 2 培训记录文件
     * @return \think\response\Json
     * @author hutao
     */
    public function delEditFile()
    {
        if(request()->isAjax()){
            $major_key = input('param.major_key');
            $types= input('param.types'); // types 1 培训材料文件 2 培训记录文件
            $edu = new EducationModel();
            $flag = $edu->removeEditFile($major_key,$types);
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/education');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/education' . DS . $exclePath;   //上传文件的地址
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
            $content_index = $edu_time_index = $address_index = $lecturer_index = $trainee_index = $num_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '培训内容'){
                    $content_index = $k;
                }else if ($str == '培训时间'){
                    $edu_time_index = $k;
                }else if ($str == '培训地点'){
                    $address_index = $k;
                }else if($str == '培训人'){
                    $lecturer_index = $k;
                }else if($str == '培训人员'){
                    $trainee_index = $k;
                }else if($str == '培训人数'){
                    $num_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($content_index == -1 || $edu_time_index == -1 || $address_index == -1 || $lecturer_index == -1 || $trainee_index == -1 || $num_index == -1 || $remark_index == -1){
                $json_data['code'] = -1;
                $json_data['info'] = '请检查标题名称';
                return json($json_data);
            }
            $insertData = [];
            $new_excel_array = delArrayNull($excel_array); // 删除空数据
            foreach($new_excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['content'] = $v[$content_index];
                    $insertData[$k]['edu_time'] = $v[$edu_time_index];
                    $insertData[$k]['address'] = $v[$address_index];
                    $insertData[$k]['lecturer'] = $v[$lecturer_index];
                    $insertData[$k]['trainee'] = $v[$trainee_index];
                    $insertData[$k]['num'] = $v[$num_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    // 非表格数据
                    $insertData[$k]['pid'] = $pid;
                    $insertData[$k]['zid'] = $zid;
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['import_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['owner'] = session('username');
                    $insertData[$k]['filename'] = $file->getInfo('name');
                    $insertData[$k]['path'] = './uploads/safety/import/education/' . str_replace("\\","/",$exclePath);
                }
            }
            $success = Db::name('safety_education')->insertAll($insertData);
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
        $majorKeyArr = input('majorKeyArr/a');
        if(count($majorKeyArr) == 0){
            return json(['code' => -1 ,'msg' => '请选择需要导出的编号']);
        }
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $name = '专题教育培训'.date('Y-m-d H:i:s'); // 导出的文件名
        $edu = new EducationModel();
        $list = $edu->getList($majorKeyArr);
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
            ->setCellValue('B1', '培训内容')
            ->setCellValue('C1', '培训时间')
            ->setCellValue('D1', '培训地点')
            ->setCellValue('E1', '培训人')
            ->setCellValue('F1', '培训人员')
            ->setCellValue('G1', '培训人数')
            ->setCellValue('H1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['major_key'])
                ->setCellValue('B'.$key, $v['content'])
                ->setCellValue('C'.$key, $v['edu_time'])
                ->setCellValue('D'.$key, $v['address'])
                ->setCellValue('E'.$key, $v['lecturer'])
                ->setCellValue('F'.$key, $v['trainee'])
                ->setCellValue('G'.$key, $v['num'])
                ->setCellValue('H'.$key, $v['remark']);
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
        $newName = '专题教育培训 - '.date('Y-m-d H:i:s'); // 导出的文件名
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '培训内容')
            ->setCellValue('C1', '培训时间')
            ->setCellValue('D1', '培训地点')
            ->setCellValue('E1', '培训人')
            ->setCellValue('F1', '培训人员')
            ->setCellValue('G1', '培训人数')
            ->setCellValue('H1', '备注');
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
            $edu = new EducationModel();
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
            $data = $con->getBiaoduanName(2); // 2 表示页面有2个一一级节点
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
                $path = "监理单位";
            }else if($pid == 0 && $id == 2 ){
                $path = "施工单位";
            }else{
                $node = new ContractModel();
                $id = $id - 10;
                $data = $node->getOneContract($id);
                if($pid == 1 && $data['biaoduan_name'] == '监理部'){
                    $path = "监理单位 >> " . $data['biaoduan_name'];
                }else{
                    $path = "施工单位 >> " . $data['biaoduan_name'];
                }
            }
            return json(['path' => $path, 'msg' => "success", 'code'=>1]);
        }
    }

}