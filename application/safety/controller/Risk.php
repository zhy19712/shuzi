<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 安全风险管理
use app\admin\controller\Base;
use app\admin\model\ContractModel;
use app\safety\model\RiskDoubleDutyModel;
use app\safety\model\RiskModel;
use think\Db;
use think\Exception;
use think\Loader;

class Risk extends Base
{
    /**
     * 预览获取一条数据  或者  编辑获取一条数据
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if (request()->isAjax()) {
            $id = input('id');
            $m = new RiskModel();
            return json($m->getOne($id));
        }
        return $this->fetch();
    }

    public function riskDuty()
    {
        if (request()->isAjax()) {
            $id = input('id');
            $risk = new RiskDoubleDutyModel();
            $data = $risk->getOne($id);
            return json($data);
        }
    }

    /**
     * 新增或修改
     * @return \think\response\Json
     * @author hutao
     */
    public function addOrEdit()
    {
        if (request()->isAjax()) {
            $edu = new RiskModel();
            $param = input('post.');
            $flag = $edu->insertOrEdit($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

    }

    public function getRisk()
    {
        $m = new RiskDoubleDutyModel();
        return json($m->getbyusername('test'));
    }

    public function riskScore()
    {
        if (request()->isAjax()) {
            try {
                $par = input('post.');
                $m = new RiskModel();
                $m->proessScore($par['username'], $par['cat'], $par['context'], $par['time']);
                return json(['code'=>1,'msg'=>'操作成功']);
            }catch (Exception $e)
            {
                return json(['code'=>-1,'msg'=>$e->getMessage()]);
            }
        }
    }



    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function riskDel()
    {
        if (request()->isAjax()) {
            $param = input('id');
            $edu = new RiskModel();
            $flag = $edu->delRisk($param);
            return json($flag);
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
        if (empty($pid) || empty($zid)) {
            return json(['code' => 1, 'data' => '', 'msg' => '请选择分组']);
        }
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/import/education');
        if ($info) {
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/safety/import/education' . DS . $exclePath;   //上传文件的地址
            // 当文件后缀是xlsx 或者 csv 就会报：the filename xxx is not recognised as an OLE file错误
            $extension = get_extension($file_name);
            if ($extension == 'xlsx') {
                $objReader = new \PHPExcel_Reader_Excel2007();
                $obj_PHPExcel = $objReader->load($file_name);
            } else if ($extension == 'xls') {
                $objReader = new \PHPExcel_Reader_Excel5();
                $obj_PHPExcel = $objReader->load($file_name);
            } else if ($extension == 'csv') {
                $PHPReader = new \PHPExcel_Reader_CSV();
                //默认输入字符集
                $PHPReader->setInputEncoding('GBK');
                //默认的分隔符
                $PHPReader->setDelimiter(',');
                //载入文件
                $obj_PHPExcel = $PHPReader->load($file_name);
            }
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $context_index = $part_index = $section_index = $cat_index = $source_index = $founder_index = $founderdate_index=$level_index=$govern_index=$governdate_index=$workdutyer_index=$completedate_index=$acceptor_idnex = -1;
            foreach ($excel_array[0] as $k => $v) {
                $str = preg_replace('/[ ]/', '', $v);
                if ($str == '隐患内容') {
                    $context_index = $k;
                } else if ($str == '隐患部位') {
                    $part_index = $k;
                } else if ($str == '责任标段') {
                    $section_index = $k;
                } else if ($str == '隐患类别') {
                    $cat_index = $k;
                } else if ($str == '隐患来源') {
                    $source_index = $k;
                } else if ($str == '发现人') {
                    $founder_index = $k;
                }else if ($str == '隐患等级') {
                    $level_index = $k;
                }else if ($str == '治理措施') {
                    $govern_index = $k;
                }else if ($str == '治理时限') {
                    $governdate_index = $k;
                }else if ($str == '施工单位治理责任人') {
                    $workdutyer_index = $k;
                }else if ($str == '治理完成时间') {
                    $completedate_index = $k;
                }else if ($str == '验收人') {
                    $acceptor_idnex = $k;
                } else if ($str == '发现日期') {
                    $founderdate_index = $k;
                }
            }
            if ($context_index == -1 || $part_index == -1 || $section_index == -1 || $cat_index == -1 || $source_index == -1 || $founder_index == -1 || $founderdate_index == -1) {
                $json_data['code'] = -1;
                $json_data['info'] = '文件内容格式不对';
                return json($json_data);
            }
            $insertData = [];
            $m=new RiskModel();
            foreach ($excel_array as $k => $v) {
                if ($k > 0) {
                    $insertData[$k]['context'] = $v[$context_index];
                    $insertData[$k]['part'] = $v[$part_index];
                    $insertData[$k]['section'] = $v[$section_index];
                    $insertData[$k]['cat'] = $v[$cat_index];
                    $insertData[$k]['source'] = $v[$source_index];
                    $insertData[$k]['founder'] = $v[$founder_index];
                    $insertData[$k]['founderdate'] = $v[$founderdate_index];
                    $insertData[$k]['level'] = $v[$level_index];
                    $insertData[$k]['govern'] = $v[$govern_index];
                    $insertData[$k]['governdate'] = $v[$governdate_index];
                    $insertData[$k]['workdutyer'] = $v[$workdutyer_index];
                    $insertData[$k]['completedate'] = $v[$completedate_index];
                    $insertData[$k]['acceptor'] = $v[$acceptor_idnex];
                }
            }
            foreach ($insertData as $item)
            {
               $success= $m->insertOrEdit($item);
            }
            if ($success) {
                return json(['code' => 1, 'data' => '', 'msg' => '导入成功']);
            } else {
                return json(['code' => -1, 'data' => '', 'msg' => '导入失败']);
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
        if (request()->isAjax()) {
            return json(['code' => 1]);
        }
        $idArr = input('param.idarr');
        $name = '安全隐患排查' . date('Y-m-d H:i:s'); // 导出的文件名
        $risk = new RiskModel();
        $list = $risk->getList($idArr);
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        /*右键属性所显示的信息*/
        $objPHPExcel->getProperties()->setCreator("zxf")//作者
        ->setLastModifiedBy("zxf")//最后一次保存者
        ->setTitle('数据EXCEL导出')//标题
        ->setSubject('数据EXCEL导出')//主题
        ->setDescription('导出数据')//描述
        ->setKeywords("excel")//标记
        ->setCategory("result file");  //类别
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '隐患内容')
            ->setCellValue('C1', '隐患部位')
            ->setCellValue('D1', '责任标段')
            ->setCellValue('E1', '隐患类别')
            ->setCellValue('F1', '隐患来源')
            ->setCellValue('G1', '发现日期')
            ->setCellValue('H1', '发现人')
            ->setCellValue('I1', '隐患等级')
            ->setCellValue('J1', '治理措施')
            ->setCellValue('K1', '治理时限')
            ->setCellValue('L1', '施工单位治理责任人')
            ->setCellValue('M1', '治理完成时间')
            ->setCellValue('N1', '验收人');
        $key = 1;
        /*以下就是对处理Excel里的数据，横着取数据*/
        foreach ($list as $v) {
            //设置循环从第二行开始
            $key++;
            $objPHPExcel->getActiveSheet()
                //Excel的第A列，name是你查出数组的键值字段，下面以此类推
                ->setCellValue('A'.$key, $v['id'])
                ->setCellValue('B'.$key, $v['context'])
                ->setCellValue('C'.$key, $v['part'])
                ->setCellValue('D'.$key, $v['section'])
                ->setCellValue('E'.$key, $v['cat'])
                ->setCellValue('F'.$key, $v['source'])
                ->setCellValue('G'.$key, $v['founderdate'])
                ->setCellValue('H'.$key, $v['founder'])
                ->setCellValue('I'.$key, $v['level'])
                ->setCellValue('J'.$key, $v['govern'])
                ->setCellValue('K'.$key, $v['governdate'])
                ->setCellValue('L'.$key, $v['workdutyer'])
                ->setCellValue('M'.$key, $v['completedate'])
                ->setCellValue('N'.$key, $v['acceptor']);
        }
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();  //清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel'); //文件类型
        header('Content-Disposition: attachment;filename="' . $name . '.xls"'); //文件名
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
        if (request()->isAjax()) {
            return json(['code' => 1]);
        }
        $name = input('param.name');
        $newName = '安全隐患排查 - ' . $name . date('Y-m-d H:i:s'); // 导出的文件名
        header("Content-type:text/html;charset=utf-8");
        Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
        //实例化
        $objPHPExcel = new \PHPExcel();
        /*右键属性所显示的信息*/
        $objPHPExcel->getProperties()->setCreator("zxf")//作者
        ->setLastModifiedBy("zxf")//最后一次保存者
        ->setTitle('数据EXCEL导出')//标题
        ->setSubject('数据EXCEL导出')//主题
        ->setDescription('导出数据')//描述
        ->setKeywords("excel")//标记
        ->setCategory("result file");  //类别
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置表格第一行显示内容
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', '隐患内容')
            ->setCellValue('B1', '隐患部位')
            ->setCellValue('C1', '责任标段')
            ->setCellValue('D1', '隐患类别')
            ->setCellValue('E1', '隐患来源')
            ->setCellValue('F1', '发现日期')
            ->setCellValue('G1', '发现人')
            ->setCellValue('H1', '隐患等级')
            ->setCellValue('I1', '治理措施')
            ->setCellValue('J1', '治理时限')
            ->setCellValue('K1', '施工单位治理责任人')
            ->setCellValue('L1', '治理完成时间')
            ->setCellValue('M1', '验收人');
        //设置当前的表格
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();  //清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel'); //文件类型
        header('Content-Disposition: attachment;filename="' . $newName . '.xls"'); //文件名
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
        if (request()->isAjax()) {
            $edu = new EducationModel();
            $years = $edu->getYears();
            return json($years);
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
        if (request()->isAjax()) {
            $con = new ContractModel();
            $data = $con->getBiaoduanName(2); // 2 表示页面有2个一一级节点
            return json($data);
        }
    }

}