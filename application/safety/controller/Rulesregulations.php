<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 9:57
 */
namespace app\safety\controller;

use app\admin\controller\Base;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\safety\model\RulesregulationsModel;
use app\safety\model\SafetySdiNodeModel;
use think\Db;
use think\Loader;
// 规章制度
class Rulesregulations extends Base
{
    /**
     * 初始化左侧树节点
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if(request()->isAjax()){
            $node = new SafetySdiNodeModel();
            $nodeStr = $node->getNodeInfo(2);
            return json($nodeStr);
        }
        return $this ->fetch();
    }

    /**
     * 获取一条编辑的数据
     * @return \think\response\Json
     * @author hutao
     */
    public function getRules()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $rules = new RulesregulationsModel();
            $data = $rules->getOne($param['id']);
            return json($data);
        }
    }

    /**
     *  从组织机构及用户树中选择负责人
     * @return \think\response\Json
     * @author hutao
     */
    public function getRuluser()
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
     * 无文件上传时的修改
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesEdit()
    {
        if(request()->isAjax()){
            $rules = new RulesregulationsModel();
            $param = input('post.');
            $data = [
                'id' => $param['id'],
                'years' => date('Y'),
                'group_id' =>  $param['group_id'],
                'number' => $param['number'],
                'rul_name' => $param['rul_name'],
                'go_date' => $param['go_date'],
                'standard' => $param['standard'],
                'evaluation' => $param['evaluation'],
                'rul_user' => $param['rul_user'],
                'remark' => $param['remark']
            ];
            $flag = $rules->editRules($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesDownload()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $id = input('param.id');
        $rules = new RulesregulationsModel();
        $param = $rules->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['rul_name'];
        // 如果是手动输入的名称，就有可能没有文件后缀
        $extension = get_extension($fileName);
        if(empty($extension)){
            $fileName = $fileName . '.' . substr(strrchr($filePath, '.'), 1);
        }
        if(file_exists($filePath)){
            $file = fopen($filePath, "r"); // 打开文件
            // 输入文件标签
            $fileName = iconv("utf-8","gb2312",$fileName);
            Header("Content-type:application/octet-stream ");
            Header("Accept-Ranges:bytes ");
            Header("Accept-Length:   " . filesize($filePath));
            Header("Content-Disposition:   attachment;   filename= " . $fileName);
            // 输出文件内容
            echo fread($file, filesize($filePath));
            fclose($file);
            exit;
        }else{
            return json(['code' => '-1','msg' => '文件不存在']);
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesDel()
    {
        if(request()->isAjax()){
            $rules = new RulesregulationsModel();
            $param = input('post.');
            $data = $rules->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); // 删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); // 删除生成的预览pdf
            }
            $flag = $rules->delRules($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 预览
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesPreview()
    {
        if(request()->isAjax()){
            $rules = new RulesregulationsModel();
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $rules->getOne($param['id']);
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
     * 获取路径
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
     * 添加或者编辑节点
     * @return \think\response\Json
     * @author hutao
     */
    public function nodeAdd()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $node = new SafetySdiNodeModel();
            $param['ptype'] = 2; // 1 法规标准识别 2 规章制度
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
                    $flag = $node->delSdinode($v['id'],2); // 1 法规标准识别 2 规章制度
                    if($flag['code'] != 1){
                        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                    }
                }
            }
            $flag = $node->delSdinode($id,2);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 获取一条节点信息
     * @return \think\response\Json
     * @author hutao
     */
    public function getOneNode()
    {
        if(request()->isAjax()){
            $id = input('post.id');
            $node = new SafetySdiNodeModel();
            $data = $node->getOneNode($id);
            return json($data);
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
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/rules');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/rules' . DS . $exclePath;   //上传文件的地址
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
            }
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $number_index = $rul_name_index = $go_date_index = $standard_index = $evaluation_index = $rul_user_index = $rul_date_index =  $remark_index = -1;
            foreach ($excel_array[0] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '标准号'){
                    $number_index = $k;
                    $rul_name_index = $k;
                }else if ($str == '名称'){
                }else if ($str == '施行日期'){
                    $go_date_index = $k;
                }else if($str == '替代标准'){
                    $standard_index = $k;
                }else if($str == '适用性评价'){
                    $evaluation_index = $k;
                }else if($str == '识别人'){
                    $rul_user_index = $k;
                }else if($str == '上传时间' || $str == '上传日期'){
                    $rul_date_index = $k;
                }else if($str == '备注'){
                    $remark_index = $k;
                }
            }
            if($number_index == -1 || $rul_name_index == -1 || $go_date_index == -1
                || $standard_index == -1 || $evaluation_index == -1 || $rul_user_index == -1
                || $rul_date_index == -1 || $remark_index == -1){
                $json_data['code'] = 0;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            foreach($excel_array as $k=>$v){
                if($k > 0){
                    $insertData[$k]['number'] = $v[$number_index];
                    $insertData[$k]['rul_name'] = $v[$rul_name_index];
                    $insertData[$k]['go_date'] = $v[$go_date_index];
                    $insertData[$k]['standard'] = $v[$standard_index];
                    $insertData[$k]['evaluation'] = $v[$evaluation_index];
                    $insertData[$k]['rul_user'] = $v[$rul_user_index];
                    $insertData[$k]['rul_date'] = $v[$rul_date_index];
                    $insertData[$k]['remark'] = $v[$remark_index];
                    $insertData[$k]['years'] = date('Y');
                    $insertData[$k]['improt_time'] = date('Y-m-d H:i:s');
                    $insertData[$k]['group_id'] = $group_id;
                }
            }
            $success = Db::name('safety_rules')->insertAll($insertData);
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
        $idArr = input('post.');
        $idArr2 = $idArr['id'];
        $name = '规章制度'.date('Y-m-d H:i:s'); // 导出的文件名 可以指定是哪个节点下的那个节点.xls 例如:规章制度-国家电网公司.xls
        $sdi = new RulesregulationsModel();
        $list = $sdi->getList($idArr2);
        $i=0;
        foreach ($list as $v){
            $v['id'] = iconv("utf-8","gb2312",$v['id']);
            $v['number'] = iconv("utf-8","gb2312",$v['number']);
            $v['rul_name'] = iconv("utf-8","gb2312",$v['rul_name']);
            $v['go_date'] = iconv("utf-8","gb2312",$v['go_date']);
            $v['standard'] = iconv("utf-8","gb2312",$v['standard']);
            $v['evaluation'] = iconv("utf-8","gb2312",$v['evaluation']);
            $v['rul_user'] = iconv("utf-8","gb2312",$v['rul_user']);
            $v['rul_date'] = iconv("utf-8","gb2312",$v['rul_date']);
            $v['remark'] = iconv("utf-8","gb2312",$v['remark']);
            $list[$i] = $v;
            $i++;
        }
        header("Content-type:text/html;charset=gb2312");
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
                ->setCellValue('C'.$key, $v['rul_name'])
                ->setCellValue('D'.$key, $v['go_date'])
                ->setCellValue('E'.$key, $v['standard'])
                ->setCellValue('F'.$key, $v['evaluation'])
                ->setCellValue('G'.$key, $v['rul_user'])
                ->setCellValue('H'.$key, $v['rul_date'])
                ->setCellValue('I'.$key, $v['remark']);
        }
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();  //清除缓冲区,避免乱码
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
            $edu = new RulesregulationsModel();
            $years = $edu->getYears();
            return json($years);
        }
    }

}