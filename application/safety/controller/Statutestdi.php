<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:53
 */
namespace app\safety\controller;

use app\admin\controller\Base;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\safety\model\SafetySdiNodeModel;
use app\safety\model\StatutestdiModel;
use think\Db;
use think\Loader;
//法规标准识别
class Statutestdi extends Base
{
    /**
     * 初始化左侧树节点
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public  function  index()
    {
        if(request()->isAjax()){
            $node = new SafetySdiNodeModel();
            $nodeStr = $node->getNodeInfo(1);
            return json($nodeStr);
        }
        return $this ->fetch();
    }

    /**
     *  从组织机构及用户树中选择负责人
     * @return \think\response\Json
     * @author hutao
     */
    public function getSiduser()
    {
        if(request()->isAjax()){
            $node1 = new UserType();
            $node2 = new UserModel();
            $nodeStr1 = $node1->getNodeInfo_1();
            $nodeStr2 = $node2->getNodeInfo_2();
            $nodeStr = "[" . substr($nodeStr1 . $nodeStr2, 0, -1) . "]";
            return json($nodeStr);
        }
    }

    /**
     * 获取一条编辑数据信息
     * @return \think\response\Json
     * @author hutao
     */
    public function getOne()
    {
        if(request()->isAjax()){
            $sdi = new StatutestdiModel();
            $data = $sdi->getOne(input('param.id'));
            return json($data);
        }
    }

    /**
     * 无文件上传的编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiEdit()
    {
        if(request()->isAjax()){
            $sdi = new StatutestdiModel();
            $param = input('post.');
            $data = [
                'id' => $param['id'],
                'years' => date('Y'),
                'group_id' => $param['group_id'],
                'sdi_number' => $param['sdi_number'],
                'sdi_name' => $param['sdi_name'],
                'go_date' => $param['go_date'],
                'standard' => $param['standard'],
                'evaluation' => $param['evaluation'],
                'sid_user' => $param['sid_user'],
                'remark' => $param['remark']
            ];
            $flag = $sdi->editSdi($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('post.id');
        $sdi = new StatutestdiModel();
        $param = $sdi->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['sdi_name'] . '.' . substr(strrchr($filePath, '.'), 1);
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

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiDel()
    {
        if(request()->isAjax()) {
            $sdi = new StatutestdiModel();
            $param = input('param.');
            $data = $sdi->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $sdi->delSdi($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 预览
     * @return \think\response\Json
     * @author hutao
     */
    public function anualPreview()
    {
        $sdi = new StatutestdiModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $sdi->getOne($param['id']);
            $path = $data['path'];
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
     * 右侧当前路径: 获取路径
     * @return \think\response\Json
     * @author hutao
     */
    public function getParents()
    {
        $node = new SafetySdiNodeModel();
        $parent = array();
        $path = "";
        if(request()->isAjax()){
            $param = input('post.');
            $id = $param['id'];
            while($id>0)
            {
                $data = $node->getOneNode($id);
                array_unshift($parent, $data['id']);
                $path = $data['pname'] . ">>" . $path;
                $id = $data['pid'];
            }
            return json(['path' => substr($path, 0 , -2), 'idList' => $parent, 'msg' => "success", 'code'=>1]);
        }
    }

    /**
     * 添加或者是编辑节点
     * @return \think\response\Json
     * @author hutao
     */
    public function nodeAdd()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $node = new SafetySdiNodeModel();
            $param['ptype'] = 1; // 1 法规标准识别 2 规章制度
            if(empty($param['id'])){
                $flag = $node->insertSdinode($param);
            }else if(!empty($param['id'])){
                $flag = $node->editSdinode($param);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 删除节点
     * @return \think\response\Json
     * @author hutao
     */
    public function nodeDel()
    {
        if(request()->isAjax()){
            $id = input('post.id');
            $node = new SafetySdiNodeModel();
            /**
             * 删除节点时，先判断该节点下是否包含子节点
             * 1，删除子节点下的所有文件
             * 2，删除子节点下
             * 3，删除该节点下的所有文件
             * 4，删除该节点
             */
            $idarr = $node->hasSubclass($id);
            if(count($idarr) > 0){
                foreach($idarr as $v){
                    $flag = $node->delSdinode($v,1); // 1 法规标准识别 2 规章制度
                    if($flag['code'] != 1){
                        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                    }
                }
            }
            $flag = $node->delSdinode($id,1);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 导入
     * @return \think\response\Json
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/statutestdi');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/statutestdi' . DS . $exclePath;   //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $number_index = $sdi_name_index = $go_date_index = $standard_index = $evaluation_index = $sid_user_index = $sdi_date_index =  $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '标准号'){
                    $number_index = $k;
                }else if ($str == '名称'){
                    $sdi_name_index = $k;
                }else if ($str == '施行日期'){
                    $go_date_index = $k;
                }else if($str == '替代标准'){
                    $standard_index = $k;
                }else if($str == '适用性评价'){
                    $evaluation_index = $k;
                }else if($str == '识别人'){
                    $sid_user_index = $k;
                }else if($str == '上传日期'){
                    $sdi_date_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($number_index == -1 || $sdi_name_index == -1
                || $go_date_index == -1 || $standard_index == -1 ||
                $evaluation_index == -1 || $sid_user_index == -1 ||
                $sdi_date_index == -1 || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['number'] = $v[$number_index];
                    $insertData[$k]['sdi_name'] = $v[$sdi_name_index];
                    $insertData[$k]['go_date'] = $v[$go_date_index];
                    $insertData[$k]['standard'] = $v[$standard_index];
                    $insertData[$k]['evaluation'] = $v[$evaluation_index];
                    $insertData[$k]['sid_user'] = $v[$sid_user_index];
                    $insertData[$k]['sdi_date'] = $v[$sdi_date_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['improt_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['group_id'] = $group_id;
                }
            }
            $success = Db::name('safety_statutesdi')->insertAll($insertData);
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
        $name = '法规标准识别'.date('Y-m-d H:i:s'); // 导出的文件名
        $sdi = new StatutestdiModel();
        $list = $sdi->getList($idArr);
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
            ->setCellValue('B1', '标准号')
            ->setCellValue('C1', '名称')
            ->setCellValue('D1', '施行日期')
            ->setCellValue('E1', '替代标准')
            ->setCellValue('F1', '适用性评价')
            ->setCellValue('G1', '识别人')
            ->setCellValue('H1', '上传日期')
            ->setCellValue('I1', '备注');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach($list as $v){
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['number'])
                ->setCellValue('C'.$key, $v['sdi_name'])
                ->setCellValue('D'.$key, $v['go_date'])
                ->setCellValue('E'.$key, $v['standard'])
                ->setCellValue('F'.$key, $v['evaluation'])
                ->setCellValue('G'.$key, $v['sid_user'])
                ->setCellValue('H'.$key, $v['sdi_date'])
                ->setCellValue('I'.$key, $v['remark']);
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
            $edu = new StatutestdiModel();
            $years = $edu->getYears();
            return json($years);
        }
    }
}