<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 外来人员
use app\admin\controller\Base;
use app\admin\model\ContractModel;
use app\safety\model\EduforeignpersonnelModel;
use think\Db;
use think\Loader;

class Eduforeignpersonnel extends Base
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
            $edu = new EduforeignpersonnelModel();
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
    public function eduForAdd()
    {
        if(request()->isAjax()){
            $edu = new EduforeignpersonnelModel();
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
            $edu = new EduforeignpersonnelModel();
            $flag = $edu->delEdu($param['major_key']);
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
        $zid = input('param.id');
        $pid = input('param.pid');
        if(empty($id) || empty($pid)){
            return  json(['code' => 1,'data' => '','msg' => '请选择分组']);
        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/eduforpnnel');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/eduforpnnel' . DS . $exclePath;   //上传文件的地址
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
            $edu_name_index = $sex_index = $id_on_index = $address_index = $approach_cause_index = $approach_time_index = $content_index = $training_time_index = -1;
            $user_index = $iphone_index = $departure_time_index = $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '名称'){
                    $edu_name_index = $k;
                }else if ($str == '性别'){
                    $sex_index = $k;
                }else if ($str == '身份证号'){
                    $id_on_index = $k;
                }else if($str == '家庭住址'){
                    $address_index = $k;
                }else if($str == '进场原因'){
                    $approach_cause_index = $k;
                }else if($str == '进场时间'){
                    $approach_time_index = $k;
                }else if($str == '培训内容'){
                    $content_index = $k;
                }else if($str == '培训时间'){
                    $training_time_index = $k;
                }else if($str == '紧急联系人'){
                    $user_index = $k;
                }else if($str == '联系电话'){
                    $iphone_index = $k;
                }else if($str == '离场时间'){
                    $departure_time_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($edu_name_index == -1 || $sex_index == -1 || $id_on_index == -1 || $address_index == -1 ||
                $approach_cause_index == -1 || $approach_time_index == -1 || $content_index == -1 || $training_time_index == -1 ||
                $user_index == -1 || $iphone_index == -1 || $departure_time_index == -1 || $remark_index == -1){
                $json_data['code'] = -1;
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
                    $insertData[$k]['address'] = $v[$address_index];
                    $insertData[$k]['approach_cause'] = $v[$approach_cause_index];
                    $insertData[$k]['approach_time'] = $v[$approach_time_index];
                    $insertData[$k]['content'] = $v[$content_index];
                    $insertData[$k]['training_time'] = $v[$training_time_index];
                    $insertData[$k]['user'] = $v[$user_index];
                    $insertData[$k]['iphone'] = $v[$iphone_index];
                    $insertData[$k]['departure_time'] = $v[$departure_time_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    // 非表格数据
                    $insertData[$k]['pid'] = $pid;
                    $insertData[$k]['zid'] = $zid;
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['import_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['owner'] = session('username');
                    $insertData[$k]['name'] = $exclePath;
                    $insertData[$k]['filename'] = $file->getInfo('name');
                    $insertData[$k]['path'] = './uploads/safety/import/eduforpnnel/' . str_replace("\\","/",$exclePath);
                }
            }
            $success = Db::name('safety_eduforeignpersonnel')->insertAll($insertData);
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
        $name = '外来人员 - '.date('Y-m-d H:i:s'); // 导出的文件名
        $edu = new EduforeignpersonnelModel();
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
            ->setCellValue('B1', '名称')
            ->setCellValue('C1', '性别')
            ->setCellValue('D1', '身份证号')
            ->setCellValue('E1', '家庭住址')
            ->setCellValue('F1', '进场原因')
            ->setCellValue('G1', '进场时间')
            ->setCellValue('H1', '培训内容')
            ->setCellValue('I1', '培训时间')
            ->setCellValue('J1', '紧急联系人')
            ->setCellValue('K1', '联系电话')
            ->setCellValue('L1', '离场时间')
            ->setCellValue('M1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['major_key'])
                ->setCellValue('B'.$key, $v['edu_name'])
                ->setCellValue('C'.$key, $v['sex'])
                ->setCellValue('D'.$key, $v['id_on'])
                ->setCellValue('E'.$key, $v['address'])
                ->setCellValue('F'.$key, $v['approach_cause'])
                ->setCellValue('G'.$key, $v['approach_time'])
                ->setCellValue('H'.$key, $v['content'])
                ->setCellValue('I'.$key, $v['training_time'])
                ->setCellValue('J'.$key, $v['user'])
                ->setCellValue('K'.$key, $v['iphone'])
                ->setCellValue('L'.$key, $v['departure_time'])
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
        $newName = '外来人员模板'; // 导出的文件名
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '名称')
            ->setCellValue('C1', '性别')
            ->setCellValue('D1', '身份证号')
            ->setCellValue('E1', '家庭住址')
            ->setCellValue('F1', '进场原因')
            ->setCellValue('G1', '进场时间')
            ->setCellValue('H1', '培训内容')
            ->setCellValue('I1', '培训时间')
            ->setCellValue('J1', '紧急联系人')
            ->setCellValue('K1', '联系电话')
            ->setCellValue('L1', '离场时间')
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
            $edu = new EduforeignpersonnelModel();
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