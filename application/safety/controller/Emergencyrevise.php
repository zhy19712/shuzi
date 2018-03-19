<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 16:30
 */
//应急评估
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EmergencyreviseModel;
use think\Db;
use think\Loader;

class Emergencyrevise extends Base
{
    /*
    * 获取一条应用设备信息
    * @return mixed|\think\response\Json
    */
    public function index()
    {
        if(request()->isAjax()){
            $emergencyrevise = new EmergencyreviseModel();
            $param = input('post.');
            $data = $emergencyrevise->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 删除一条应急评估信息
     * @return mixed|\think\response\Json
     */
    public function equipmentDel()
    {
        $emergencyrevise = new EmergencyreviseModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $emergencyrevise->delEmergencyrevise($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * [versionnumberpathPreview 原有版本文件预览]
     */

    public function versionnumberpathPreview()
    {
        $emergencyrevise = new EmergencyreviseModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $emergencyrevise->getOne($param['id']);
            if(!empty($data['version_number_path']))
            {
                $path = $data['version_number_path'];
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
            }else
            {
                return json(['code' => $code,'msg' => '文件不存在']);
            }

        }
    }

    /**
     * [alternativeversionpathPreview 替换版本文件预览]
     */

    public function alternativeversionpathPreview()
    {
        $emergencyrevise = new EmergencyreviseModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $emergencyrevise->getOne($param['id']);
            if(!isset($data['alternative_version_path']))
            {
                $path = $data['alternative_version_path'];
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
            }else
            {
                return json(['code' => $code,'msg' => '文件不存在']);
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
        $idArr = input('param.idarr');
        $name = '应急评估'.date('Y-m-d H:i:s'); // 导出的文件名
        $emergencyrevise = new EmergencyreviseModel();
        $list = $emergencyrevise->getList($idArr);
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
            ->setCellValue('B1', '文件名称')
            ->setCellValue('C1', '原有版本号')
            ->setCellValue('D1', '替换版本号')
            ->setCellValue('E1', '替换时间')
            ->setCellValue('F1', '上传人');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['preplan_file_name'])
                ->setCellValue('C'.$key, $v['version_number'])
                ->setCellValue('D'.$key, $v['alternative_version'])
                ->setCellValue('E'.$key, $v['date'])
                ->setCellValue('F'.$key, $v['owner']);
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


}