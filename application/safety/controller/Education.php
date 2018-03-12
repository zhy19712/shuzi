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
            $edu = new EducationModel();
            $param = input('post.');
            if(empty($param['id'])){
                $param['owner'] = session('username');
                $param['edu_date'] = date("Y-m-d H:i:s");
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
            $edu = new EducationModel();
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
        $group_id = input('param.group_id');
        if(empty($group_id)){
            return  json(['code' => 1,'data' => '','msg' => '请选择分组']);
        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/education');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/education' . DS . $exclePath;   //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $content_index = $edu_time_index = $address_index = $lecturer_index = $trainee_index = $num_index = -1;
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
                }
            }
            if($content_index == -1 || $edu_time_index == -1 || $address_index == -1 || $lecturer_index == -1 || $trainee_index == -1 || $num_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['content'] = $v[$content_index];
                    $insertData[$k]['edu_time'] = $v[$edu_time_index];
                    $insertData[$k]['address'] = $v[$address_index];
                    $insertData[$k]['lecturer'] = $v[$lecturer_index];
                    $insertData[$k]['trainee'] = $v[$trainee_index];
                    $insertData[$k]['num'] = $v[$num_index];
                    // 年度
                    $insertData[$k]['years'] = date('Y-m-d H:i:s');
                    $insertData[$k]['group_id'] = $group_id;
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
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $idArr = input('param.idarr');
        $name = '专题教育培训'.date('Y-m-d H:i:s'); // 导出的文件名
        $edu = new EducationModel();
        $list = $edu->getList($idArr);
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
            ->setCellValue('B1', '培训内容')
            ->setCellValue('C1', '培训时间')
            ->setCellValue('D1', '培训地点')
            ->setCellValue('E1', '培训人')
            ->setCellValue('F1', '培训人员')
            ->setCellValue('G1', '培训人数');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['content'])
                ->setCellValue('C'.$key, $v['edu_time'])
                ->setCellValue('D'.$key, $v['address'])
                ->setCellValue('E'.$key, $v['lecturer'])
                ->setCellValue('F'.$key, $v['trainee'])
                ->setCellValue('G'.$key, $v['num']);
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
     * 查看历史版本
     * @return \think\response\Json
     * @author hutao
     */
    public function getHistory()
    {
        if(request()->isAjax()){
            $edu = new EducationModel();
            $years = $edu->getYears();
            return json($years);
        }
    }

    /**
     * 获取标段
     * @return \think\response\Json
     * @author hutao
     */
    public function getSegment()
    {
        if(request()->isAjax()){
            $con = new ContractModel();
            $data = $con->getBiaoduanName();
            return json($data);
        }
    }

}