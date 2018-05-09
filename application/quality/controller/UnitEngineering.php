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
use app\admin\model\HunningtuModel;
use app\admin\model\KaiwaModel;
use app\admin\model\MaoganModel;
use app\admin\model\ProjectModel;
use app\admin\model\ProjectScupperModel;
use app\admin\model\ZhihuModel;
use app\quality\model\ProjectAttachmentModel;
use app\quality\model\StandardDeviationModel;

/**
 * 质量验收管理  --  单元工程
 * Class DataStatisticalAnalysis
 * @package app\quality\controller
 * @author hutao
 */
class UnitEngineering extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $node1 = new DivideModel();
            $node2 = new ProjectModel();
            $nodeStr1 = $node1->getNodeInfo_4();
            $nodeStr2 = $node2->getNodeInfo_5();
            $nodeStr = "[" . substr($nodeStr1 . $nodeStr2, 0, -1) . "]";
            return json($nodeStr);
        }
        return $this->fetch();
    }

    /**
     * [获取单元工程及验收批次基础信息]
     * @return [type] [description]
     */
    public function fetchData()
    {
        $project = new ProjectModel();
        $kaiwa = new KaiwaModel();
        $hunningtu = new HunningtuModel();
        $zhihu = new ZhihuModel();
        $maogan = new MaoganModel();
        $scupper = new ProjectScupperModel();
        if(request()->isAjax()){
            $param = input('post.');
            $projectData = $project->getOneProject($param['uid']);
            $p = urldecode(urldecode($param['cate']));
            if($p==="开挖" || $p=="明挖" || $p=="洞挖")
            {
                $kaiwaData = $kaiwa->getOne($param['uid']);
                return json(['projectData' => $projectData, 'kaiwaData' => $kaiwaData,'code' => 1]);
            }else if($p=='支护')
            {
                $zhihuData = $zhihu->getOne($param['uid']);
                return json(['projectData' => $projectData, 'zhihuData' => $zhihuData,'code' => 1]);
            }else if($p=='混凝土')
            {
                $hunningtuData = $hunningtu->getOne($param['uid']);
                return json(['projectData' => $projectData, 'hunningtuData' => $hunningtuData,'code' => 1]);
            }else if($p=='锚杆')
            {
                $maoganData = $maogan->getOne($param['uid']);
                return json([ 'maoganData' => $maoganData,'code' => 1]);
            }else if($p=='排水孔')
            {
                $scupperData = $scupper->getOne($param['uid']);
                return json(['projectData' => $projectData, 'scupperData' => $scupperData,'code' => 1]);
            }

        }
    }


    /**
     * [保存单元工程验收批次信息]
     * @return [type] [description]
     */
    public function dataAdd()
    {
        $kaiwa = new KaiwaModel();
        $zhihu = new ZhihuModel();
        $hunningtu = new HunningtuModel();
        $maogan = new MaoganModel();
        $project = new ProjectModel();
        $scupper = new ProjectScupperModel();

        $param = input('post.');
        if(request()->isAjax()){
            if($param['cate'] != '锚杆'){
                $projectData = [
                    'id' => $param['uid'],
                    'pingding_date' => $param['evaluated_date']
                ];
                $project->editProject($projectData);
                acceptanceWarning();//启动时刷新验收预警
            }
            if(empty($param['edit']) && ($param['cate']=='开挖' || $param['cate']=='明挖' || $param['cate']=='洞挖'))
            {
                $flag = $kaiwa->insertKaiwa($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='支护')
            {
                $flag = $zhihu->insertZhihu($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='混凝土')
            {
                $flag = $hunningtu->insertHunningtu($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='锚杆')
            {
                $flag = $maogan->insertMaogan($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='排水孔')
            {
                $flag = $scupper->insertScupper($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit']) && ($param['cate']=='开挖' || $param['cate']=='明挖' || $param['cate']=='洞挖'))
            {
                $flag = $kaiwa->editKaiwa($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='支护')
            {
                $flag = $zhihu->editZhihu($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='混凝土')
            {
                $flag = $hunningtu->editHunningtu($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='锚杆')
            {
                $flag = $maogan->editMaogan($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='排水孔')
            {
                $flag = $scupper->editScupper($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
        }
        return $this->fetch();
    }


    /**
     * [锚杆数据删除]
     */
    public function maoganDel()
    {
        $id = input('param.id');
        $maogan = new MaoganModel();
        $flag = $maogan->delMaogan($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [获取当前节点的所有父级]
     * @return [type] [description]
     */
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


    /**
     * [删除附件]
     */
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



    //附件下载
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


    //分部工程质量台账
    public function level3Quality()
    {
        $level = '';
        $level4 = new DivideModel();
        $level5 = new ProjectModel();
        $level5_kaiwa = new KaiwaModel();
        $level5_zhihu = new ZhihuModel();
        $level5_hunningtu = new HunningtuModel();
        $num = array();
        $qualified_num = array();
        $good_num = array();
        $good_rate = array();
        $level4_name = array();
        if(request()->isAjax()) {
            $param = input('post.');
            $pid = $param['id'];
            $level4_data = $level4->getAllbyPID($pid);
            $level5_num_primary = 0;
            $level5_qualified_num_primary = 0;
            $level5_good_num_primary = 0;
            foreach($level4_data as $dd){
                $level5_num = 0;
                $level5_qualified_num = 0;
                $level5_good_num = 0;
                array_push($level4_name, $dd['name']);             //单元工程名
                $level5_data = $level5->getAllbyPID($dd['id']);    //全部单元工程
                $level5_data_primary =  $level5->getAllbyPIDandPrimary($dd['id']); //主要单元工程
                foreach($level5_data as $data) {
                    if ($data['cate'] == '开挖' || $data['cate']=="明挖" || $data['cate'] == "洞挖") {
                        $level5_num += $level5_kaiwa->getNum($data['id']);
                        $level5_qualified_num += $level5_kaiwa->getQualifiedNum($data['id']);
                        $level5_good_num += $level5_kaiwa->getGoodNum($data['id']);

                    }else if($data['cate'] == '支护'){
                        $level5_num += $level5_zhihu->getNum($data['id']);
                        $level5_qualified_num += $level5_zhihu->getQualifiedNum($data['id']);
                        $level5_good_num += $level5_zhihu->getGoodNum($data['id']);
                    }else if($data['cate'] == '混凝土'){
                        $level5_num += $level5_hunningtu->getNum($data['id']);
                        $level5_qualified_num += $level5_hunningtu->getQualifiedNum($data['id']);
                        $level5_good_num += $level5_hunningtu->getGoodNum($data['id']);
                    }
                }

                foreach($level5_data_primary as $data_primary) {
                    if ($data_primary['cate'] == '开挖' || $data_primary['cate'] == '明挖' || $data_primary['cate'] == '洞挖') {
                        $level5_num_primary += $level5_kaiwa->getNum($data_primary['id']);
                        $level5_qualified_num_primary += $level5_kaiwa->getQualifiedNum($data_primary['id']);
                        $level5_good_num_primary += $level5_kaiwa->getGoodNum($data_primary['id']);

                    }else if($data_primary['cate'] == '支护'){
                        $level5_num_primary += $level5_zhihu->getNum($data_primary['id']);
                        $level5_qualified_num_primary += $level5_zhihu->getQualifiedNum($data_primary['id']);
                        $level5_good_num_primary += $level5_zhihu->getGoodNum($data_primary['id']);
                    }else if($data_primary['cate'] == '混凝土'){
                        $level5_num_primary += $level5_hunningtu->getNum($data_primary['id']);
                        $level5_qualified_num_primary += $level5_hunningtu->getQualifiedNum($data_primary['id']);
                        $level5_good_num_primary += $level5_hunningtu->getGoodNum($data_primary['id']);
                    }
                }

                array_push($num, $level5_num);
                array_push($qualified_num, $level5_qualified_num);
                array_push($good_num, $level5_good_num);
                if($level5_num>0){
                    array_push($good_rate, floor($level5_good_num/$level5_num*100)/100);
                }else{
                    array_push($good_rate,0);
                }

            }

            //合计数目
            array_push($num, array_sum($num));
            array_push($qualified_num, array_sum($qualified_num));
            array_push($good_num, array_sum($good_num));
            if(end($num)>0){
                array_push($good_rate,  floor(end($good_num)/end($num)*100)/100);
            }else{
                array_push($good_rate,0);
            }


            //主要单位工程数目
            array_push($num, $level5_num_primary);
            array_push($qualified_num, $level5_qualified_num_primary);
            array_push($good_num, $level5_good_num_primary);
            if($level5_num_primary>0){
                array_push($good_rate,  floor($level5_good_num_primary/$level5_num_primary*100)/100);
            }else{
                array_push($good_rate,0);
            }

            if(!empty($param['accident'])){
                $level4->editNode($param);
            }
            $level3_data = $level4->getOnebyID($pid);
            $accident = $level3_data['accident'];
            $primary = $level3_data['primary'];
            //计算优良等级
            if($num[count($num)-2] != 0 && $num[count($num)-2] == ($qualified_num[count($qualified_num)-2] + $good_num[count($good_num)-2])){
                $level = '合格';
                if($level5_num_primary == 0 && $accident == '否' && $good_rate[count($good_rate)-1]){
                    $level = '优良';
                }
                if(end($good_rate) >= 0.9 && $accident == '否' && $good_rate[count($good_rate)-1]){
                    $level = '优良';
                }
            }else{
                $level = '尚未评定';
            }

            $param['level'] = $level;
            $level4->editNode($param);


            return json(['code' => 1, 'column1' => $level4_name, 'column2' => $num, 'column3' => $qualified_num, 'column4' => $good_num, 'column5' => $good_rate, 'primary' => $primary, 'accident' => $accident, 'level' => $level]);

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



    //改变是否为主要分布/单元工程的值
    public function changePrimary(){
        $level3 = new DivideModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $level3->editNode($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    // 新增或修改强度值
    public function editStandardDeviation()
    {
        if(request()->isAjax()){
            $param = input('post.');
            // 前台需要传递的参数 genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            // 前台需要传递的参数 intensity_value(是数组) 每个检测组的试验强度值 standard_value 设计强度标准值

            // 验证规则
            $rule = [
                ['genre', 'require|number|gt:-1', '请选择所属的工程类型|工程类型的编号只能是数字|工程类型的编号不能为负数'],
                ['gid', 'require|number|gt:-1', '请选择所属的工程类型|工程类型的编号只能是数字|工程类型的编号不能为负数'],
                ['unit_type', 'require|number|gt:-1', '请选择所属单位|所属单位的编号只能是数字|所属单位的编号不能为负数'],
                ['type', 'require|number|gt:-1', '请选择强度类型|强度类型的编号只能是数字|强度类型的编号不能为负数'],
                ['intensity_value', 'require', '请填写试验强度值'],
                ['standard_value', 'require|number|gt:-1', '请填写设计强度标准值|设计强度标准值只能是数字|设计强度标准值不能为负数'],
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $intensity_arr = explode(',',$param['intensity_value']);
            $intensity_arr = array_filter($intensity_arr);
            if(sizeof($intensity_arr) < 1){
                return json(['code' => '-1', 'data' => [], 'msg' => '请填写试验强度值']);
            }
            $data = [];
            $genre = $param['genre']; $gid = $param['gid']; $unit_type = $param['unit_type']; $type = $param['type']; $standard_value = $param['standard_value'];
            foreach ($intensity_arr as $k=>$v){
                $data[$k]['genre'] = $genre;
                $data[$k]['gid'] = $gid;
                $data[$k]['unit_type'] = $unit_type;
                $data[$k]['type'] = $type;
                $data[$k]['intensity_value'] = $v;
                $data[$k]['standard_value'] = $standard_value;
            }
            $level3 = new StandardDeviationModel();
            $flag = $level3->insertMater($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

}