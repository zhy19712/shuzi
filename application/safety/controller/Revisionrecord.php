<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:56
 */

namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\RevisionrecordModel;
use think\Db;
use think\Loader;
//修编记录
class Revisionrecord extends Base
{
    public  function  index()
    {
        return $this ->fetch();
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function recordDel()
    {
        if(request()->isAjax()){
            $record = new RevisionrecordModel();
            $param = input('post.');
            $flag = $record->delRecord($param['major_key']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
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
    public function recordDownload()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $majorKeyArr = input('majorKeyArr/a');
        $name = '修编记录 - '.date('Y-m-d H:i:s'); // 导出的文件名
        $record = new RevisionrecordModel();
        $list = $record->getList($majorKeyArr);
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
            ->setCellValue('F1', '上传人')
            ->setCellValue('G1', '类别');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['major_key'])
                ->setCellValue('B'.$key, $v['record_name'])
                ->setCellValue('C'.$key, $v['original_number'])
                ->setCellValue('D'.$key, $v['replace_number'])
                ->setCellValue('E'.$key, $v['replace_time'])
                ->setCellValue('F'.$key, $v['owner'])
                ->setCellValue('G'.$key, $v['record_type']);
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