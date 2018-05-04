<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\quality\controller;
use app\admin\controller\Base;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;
use app\quality\model\ProjectAttachmentModel;

/**
 * 质量验收管理  --  单位工程
 * Class DataStatisticalAnalysis
 * @package app\quality\controller
 * @author hutao
 */
class UnitProject extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $node1 = new DivideModel();
            $nodeStr1 = $node1->getNodeInfo_4(2);
            $nodeStr = "[" . substr($nodeStr1, 0, -1) . "]";
            return json($nodeStr);
        }
        return $this->fetch();
    }

    public function getParents()
    {
        $project = new ProjectModel();
        $node = new DivideModel();
        $parent = array();
        $path = "";
        $id="";
        if(request()->isAjax()){
            $param = input('post.');
            if(!empty($param['uid'])){
                $uid = $param['uid'];
                $temp = $project->getOneProject($uid);
                $id = $temp['pid'];
                $path = $temp['name'] . ">>";
                array_unshift($parent, $temp['id']);
                unset($temp);
            }else{
                $id = $param['id'];
            }
            while($id>0)
            {
                $data = $node->getOneNode($id);
                array_unshift($parent, $data['id']);
                $path = $data['name'] . ">>" . $path;
                $id = $data['pid'];
                $data = array();
            }
            return json(['code' => 1, 'path' => substr($path, 0, -2), 'idList' => $parent]);
        }
    }

    // 删除附件
    public function attachmentDel()
    {
        $param = input('post.');
        if(request()->isAjax()) {
            $id = $param['id'];
            $attachment = new ProjectAttachmentModel();
            $flag = $attachment->delAttachment($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    // 预览附件
    public function attachmentPreview()
    {
        $attachment = new ProjectAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $attachment->getOne($param['id']);
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

    // 附件下载
    public function attachmentDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new ProjectAttachmentModel();
        $param = $attachment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['filename'];
        if(file_exists($filePath)) {
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

    //单位工程质量台账
    public function level2Quality()
    {
        $level = '';
        $level3 = new DivideModel();
        $num = array();
        $qualified_num = array();
        $good_num = array();
        $good_rate = array();
        $level3_name = array();
        $level3_quality = array();
        $score = array();
        if(request()->isAjax()) {
            $param = input('post.');
            $pid = $param['id'];
            $level3_num = $level3->getNum($pid);                       //获取单位工程包含的分部工程个数
            $level3_num_primary = $level3->getNumPrimary($pid);       //获取主要分部工程个数
            $level3_qualified_num = $level3->getQualifiedNum($pid);
            $level3_qualified_num_primary = $level3->getQualifiedNumPrimary($pid);
            $level3_good_num = $level3->getGoodNum($pid);
            $level3_good_num_primary = $level3->getGoodNumPrimary($pid);
            $level3_data = $level3->getAllbyPID($pid);                //获取单位工程下的所有分部工程

            foreach($level3_data as $data){
                array_push($level3_name, $data['name']);             //分部工程名
                array_push($level3_quality, $data['level']);             //分部工程质量等级
            }

            //合计
            array_push($num, $level3_num);
            array_push($qualified_num, $level3_qualified_num);
            array_push($good_num, $level3_good_num);
            if($level3_num>0){
                array_push($good_rate,  floor($level3_good_num/$level3_num*100)/100);
            }else{
                array_push($good_rate,0);
            }



            //主要分部工程
            array_push($num, $level3_num_primary);
            array_push($qualified_num, $level3_qualified_num_primary);
            array_push($good_num, $level3_good_num_primary);
            if($level3_num_primary>0){
                array_push($good_rate,  floor($level3_good_num_primary/$level3_num_primary*100)/100);
            }else{
                array_push($good_rate,0);
            }



            if(!empty($param['accident'])){
                $level3->editNode($param);
            }
            $level2_data = $level3->getOnebyID($pid);
            $accident = $level2_data['accident'];

            //外观质量得分
            if(!empty($param['score_design'])&&!empty($param['score_actual'])&&!empty($param['score'])){
                $level3->editNode($param);
            }
            $level2_data = $level3->getOnebyID($pid);
            array_push($score, $level2_data['score_design']);
            array_push($score, $level2_data['score_actual']);
            array_push($score, $level2_data['score']);

            //计算优良等级
            if($num[count($num)-2] == ($qualified_num[count($qualified_num)-2] + $good_num[count($good_num)-2])){
                $level = '合格';
                //主要分布工程数目为0的情况
                if(end($score)!= null){
                    if($level3_num_primary == 0 && $accident == '否' && $good_rate[count($good_rate)-2] >= 0.7 && end($score) >= 85){
                        $level = '优良';
                    }
                    if(end($good_rate) == 1 && $accident == '否' && $good_rate[count($good_rate)-2] >= 0.7 && end($score) >= 85){
                        $level = '优良';
                    }
                }else{
                    $level = '尚未评定';
                }

            }else{
                $level = '尚未评定';
            }

            $param['level'] = $level;
            $level3->editNode($param);


            return json(['code' => 1, 'column1' => $num, 'column2' => $qualified_num, 'column3' => $good_num, 'column4' => $good_rate, 'accident' => $accident, 'score' => $score, 'name' => $level3_name, 'quality' => $level3_quality, 'level' => $level]);

        }
    }

}