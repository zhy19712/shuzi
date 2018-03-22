<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 15:55
 */
//设备管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\DevicemanagementModel;
use think\Db;
use think\Loader;

class Devicemanagement extends Base
{
    /*
     * 获取一条设备管理信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $devicemanagement = new DevicemanagementModel();
            $param = input('post.');
            $data = $devicemanagement->getOne($param['id']);
            //文件名、图片名、文件路径，图片路径不为空时进行拆解
            if(!empty($data['filename']))
            {
                $data['filename'] = explode("☆",$data['filename']);//拆解拼接的文件、图片名
            }else
            {
                $data['filename'] = array();//为空时返回一个空数组
            }

            if(!empty($data['path']))
            {
                $data['path'] = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            }else
            {
                $data['path'] = array();//为空时返回一个空数组
            }

            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条设备管理信息
     */
    public function  devicemanagementEdit()
    {
        $devicemanagement = new DevicemanagementModel();

        $param = input('post.');

        $pathImgName = input('post.pathImgName/a');//获取post传过来的多个文件、图片的名字，包含在一个一维数组中。
        $pathImgArr = input('post.pathImgArr/a');//获取post传过来的多个文件、图片的路径，包含在一个一维数组中。
        $pathImgDel = input('post.pathImgDel/a');//获取post传过来要删除的多个文件、图片的路径，包含在一个一维数组中。

        //判断文件名、图片名、路径是否为空，为空的时候不拼接

        if(!empty($pathImgName))
        {
            $pathImgName = implode("☆",$pathImgName);//上传所有文件图片的拼接名
        }
        if(!empty($pathImgArr))
        {
            $pathImgArr = implode("☆",$pathImgArr);//上传所有文件、图片拼接路径

        }

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
//                    'selfid' => $param['selfid'],//区别类别
                    'asset_coding' => $param['asset_coding'],//资产编码
                    'asset_name' => $param['asset_name'],//资产名称
                    'use_department' => $param['use_department'],//使用部门
                    'user' => $param['user'],//使用人
                    'start_using_time' => $param['start_using_time'],//开始使用时间
                    'equipment_state' => $param['equipment_state'],//设备状态
                    'specification_model' => $param['specification_model'],//规格型号
                    'original_value' => $param['original_value'],//原值
                    'date_production' => $param['date_production'],//出厂日期
                    'factory_number' => $param['factory_number'],//出厂编号
                    'place_storage' => $param['place_storage'],//存放地点
                    'use_status' => $param['use_status'],//使用状况
                    'inspection_requirements' => $param['inspection_requirements'],//检验需求
                    'validity_inspection' => $param['validity_inspection'],//检验有效期
//                    'input_time' => $param['input_time'],

                    'filename' => $pathImgName,//拼接文件名、图片名

                    'path' => $pathImgArr,//拼接文件路径、图片路径

                    'remark' => $param['remark'],//备注
                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $devicemanagement->insertDevicemanagement($data);


                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
//            else {
//
//
//                $data = [
//                    'id' => $param['id'],
////                    'selfid' => $param['selfid'],//区别类别
//                    'name' => $param['name'],//姓名
//                    'job_name' => $param['job_name'],//分包单位/作业队
//                    'sex' => $param['sex'],//性别
//                    'age' => $param['age'],//年龄
//                    'special_type_work' => $param['special_type_work'],//特殊工种
//                    'job_certificate' => $param['job_certificate'],//职业资格证书名称
//                    'card_number' => $param['card_number'],//身份证号
//                    'issuing_unit' => $param['issuing_unit'],//发证单位
//                    'date_evidence' => $param['date_evidence'],//取证日期
//                    'effective_date' => $param['effective_date'],//有效日期
//                    'certificate_number' => $param['certificate_number'],//证书编号
//                    'advance_retreat_time' => $param['advance_retreat_time'],//进退场时间
//                    'document_status' => $param['document_status'],//证件状态
//
//                    'filename' => $pathImgName,//拼接文件名、图片名
//
//                    'path' => $pathImgArr,//拼接文件路径、图片路径
//
//                    'remark' => $param['remark'],//备注
////                    'date' => date("Y-m-d H:i:s")//添加时间
//                ];
//                $flag = $special->editSpecialoperate($data);
//                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
//            }

        }
    }

    /*
     * 删除一条设备管理信息
     */
    public function devicemanagementDel()
    {
        $devicemanagement = new DevicemanagementModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $devicemanagement->getOne($param['id']);

            if(!empty($data['path']))
            {
                $path = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            }

            foreach ((array)$path as $v)
            {
                unlink($v); //删除文件、图片
            }
            $flag = $devicemanagement->delDevicemanagement($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    /*
     *
     * 根据条件筛选选中的条数
     */
    public function getcount()
    {
        $devicemanagement = new DevicemanagementModel();

        $where = "";//定义一个空数组

        if(request()->isAjax()) {
            $param = input('post.');

            $selfid = $param['selfid'];//类别id
            $year = $param['year'];//年份
            $history_version = $param['history_version'];

            if(!empty($selfid))
            {
                $where .= "selfid =  '$selfid' ";
            }

            if(!empty($year))
            {
                $where .= " and date like '%" .$year. "%' ";
            }

            if(!empty($history_version))
            {
                $where .= " and input_time like '%" .$history_version. "%' ";
            }


            $flag = $devicemanagement->getallcount($where);

            return json(['code' => 1, 'data' => $flag, 'msg' => $flag['msg']]);

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
        $devicemanagement = new DevicemanagementModel();
        $idArr = input('param.idarr/a');
        if($idArr['0'] == "all")
        {
            $idArr = $devicemanagement ->getallid();
        }
        $name = '设备管理'.date('Y-m-d H:i:s'); // 导出的文件名

        $list = $devicemanagement->getList($idArr);
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
            ->setCellValue('B1', '资产名称')
            ->setCellValue('C1', '使用部门')
            ->setCellValue('D1', '使用人')
            ->setCellValue('E1', '存放地点')
            ->setCellValue('F1', '设备状态')
            ->setCellValue('G1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['asset_name'])
                ->setCellValue('C'.$key, $v['use_department'])
                ->setCellValue('D'.$key, $v['user'])
                ->setCellValue('E'.$key, $v['place_storage'])
                ->setCellValue('F'.$key, $v['equipment_state'])
                ->setCellValue('G'.$key, $v['remark']);
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
        $newName = '设备管理 - '.$name.date('Y-m-d H:i:s'); // 导出的文件名
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
            ->setCellValue('B1', '资产名称')
            ->setCellValue('C1', '使用部门')
            ->setCellValue('D1', '使用人')
            ->setCellValue('E1', '存放地点')
            ->setCellValue('F1', '设备状态')
            ->setCellValue('G1', '备注');
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