<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 17:31
 */
//内部设备设施管理,绝缘安全工器具
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\InsulatinginnerModel;
use think\Db;
use think\Loader;

class Insulatinginner extends Base
{
    /*
     * 获取一条绝缘工器具信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $insulating = new InsulatinginnerModel();
            $param = input('post.');
            $data = $insulating->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 编辑一条绝缘工器具信息
     */
    public function  insulatinginnerEdit()
    {
        $insulating = new InsulatinginnerModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'tool_name' => $param['tool_name'],//工器具名称
                'type_model' => $param['type_model'],//规格型号
                'number' => $param['number'],//数量
                'batch' => $param['batch'],//批次
                'manufacture' => $param['manufacture'],//生产厂家
                'date_product' => $param['date_product'],//出厂日期
                'check_round' => $param['check_round'],//定检周期
                'first_check_date' => $param['first_check_date'],//首检日期
                'use_position' => $param['use_position'],//使用位置
                'remark' => $param['remark']//备注
            ];
            $flag = $insulating->editInsulatinginner($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * 删除一条绝缘工器具信息
     */
    public function insulatinginnerDel()
    {
        $insulating = new InsulatinginnerModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $insulating->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $insulating->delInsulatinginner($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
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
        $insulating = new InsulatinginnerModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $insulating ->getallid();
        }
        $name = '绝缘工器具'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $insulating->getList($idArr);
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
            ->setCellValue('B1', '工器具名称')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '数量')
            ->setCellValue('E1', '批次')
            ->setCellValue('F1', '生产厂家')
            ->setCellValue('G1', '出厂日期')
            ->setCellValue('H1', '定检周期')
            ->setCellValue('I1', '首检日期')
            ->setCellValue('J1', '使用位置')
            ->setCellValue('K1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['tool_name'])
                ->setCellValue('C'.$key, $v['type_model'])
                ->setCellValue('D'.$key, $v['number'])
                ->setCellValue('E'.$key, $v['batch'])
                ->setCellValue('F'.$key, $v['manufacture'])
                ->setCellValue('G'.$key, $v['date_product'])
                ->setCellValue('H'.$key, $v['check_round'])
                ->setCellValue('I'.$key, $v['first_check_date'])
                ->setCellValue('J'.$key, $v['use_position'])
                ->setCellValue('K'.$key, $v['remark']);
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
        $newName = '绝缘工器具 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
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
            ->setCellValue('B1', '工器具名称')
            ->setCellValue('C1', '规格型号')
            ->setCellValue('D1', '数量')
            ->setCellValue('E1', '批次')
            ->setCellValue('F1', '生产厂家')
            ->setCellValue('G1', '出厂日期')
            ->setCellValue('H1', '定检周期')
            ->setCellValue('I1', '首检日期')
            ->setCellValue('J1', '使用位置')
            ->setCellValue('K1', '备注');
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