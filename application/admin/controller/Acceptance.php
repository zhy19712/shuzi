<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\admin\controller;
use app\admin\model\MaoganModel;
use app\admin\model\ProjectAttachmentModel;
use app\admin\model\ZhihuModel;
use think\Db;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;
use app\admin\model\KaiwaModel;
use app\admin\model\HunningtuModel;


class Acceptance extends Base
{
    //质量验收界面组合五级树结构
    public function index()
    {
        if(request()->isAjax()){
            $node1 = new DivideModel();
            $node2 = new ProjectModel();
            $nodeStr1 = $node1->getNodeInfo_4();
            $nodeStr2 = $node2->getNodeInfo_5();
            $nodeStr = "[" . substr($nodeStr1 . $nodeStr2, 0, -1) . "]";
            return json($nodeStr);}
        else
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
        if(request()->isAjax()){
            $param = input('post.');
            $projectData = $project->getOneProject($param['uid']);
            $p = urldecode(urldecode($param['cate']));
            if($p==="开挖")
            {
                $kaiwaData = $kaiwa->getOne($param['uid']);
                return json(['projectData' => $projectData, 'kaiwaData' => $kaiwaData,'msg' => "success"]);
            }else if($p=='支护')
            {
                $zhihuData = $zhihu->getOne($param['uid']);
                return json(['projectData' => $projectData, 'zhihuData' => $zhihuData,'msg' => "success"]);
            }else if($p=='混凝土')
            {
                $hunningtuData = $hunningtu->getOne($param['uid']);
                return json(['projectData' => $projectData, 'hunningtuData' => $hunningtuData,'msg' => "success"]);
            }else if($p=='锚杆')
            {
                $maoganData = $maogan->getOne($param['uid']);
                return json([ 'maoganData' => $maoganData,'msg' => "success"]);
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

        $param = input('post.');
        if(request()->isAjax()){
            if($param['cate'] != '锚杆'){
                $projectData = [
                    'id' => $param['uid'],
                    'pingding_date' => $param['evaluated_date']
                ];
                $project->editProject($projectData);
            }
            if(empty($param['edit'])&&$param['cate']=='开挖')
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
            else if(!empty($param['edit'])&&$param['cate']=='开挖')
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
            }else if(!empty($param['edit'])&&$param['cate']=='锚杆')
            {
                $flag = $maogan->editMaogan($param);
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
            return json(['path' => substr($path, 0, -2), 'idList' => $parent, 'msg' => "success"]);
        }
    }

    //保存上传附件信息
    public function saveAttachmentInfo()
    {
        $attachment = new ProjectAttachmentModel();

        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id_level_1' => $param['id_level_1'],
                'id_level_2' => $param['id_level_2'],
                'id_level_3' => $param['id_level_3'],
                'id_level_4' => $param['id_level_4'],
                'id_level_5' => $param['id_level_5'],
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'department' => session('dept'),
                'path' => $param['path'],
                'filename' => $param['filename']
            ];
            $flag = $attachment->insertAttachment($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
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
            $data = $attachment->getOne($id);
            $path = $data['path'];
            unlink($path); //删除文件
            $flag = $attachment->delAttachment($id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


    //附件下载
    public function attachmentDownload()
    {
        $id = input('param.id');
        $attachment = new ProjectAttachmentModel();
        $param = $attachment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['filename'];
        $file = fopen($filePath, "r"); //   打开文件
        //输入文件标签
        Header("Content-type:application/octet-stream ");
        Header("Accept-Ranges:bytes ");
        Header("Accept-Length:   " . filesize($filePath));
        Header("Content-Disposition:   attachment;   filename= " . $fileName);

        //   输出文件内容
        echo fread($file, filesize($filePath));
        fclose($file);
        exit;
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
                        if ($data['cate'] == '开挖') {
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
                        if ($data_primary['cate'] == '开挖') {
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


            return json(['column1' => $level4_name, 'column2' => $num, 'column3' => $qualified_num, 'column4' => $good_num, 'column5' => $good_rate, 'primary' => $primary, 'accident' => $accident, 'level' => $level]);

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


            return json(['column1' => $num, 'column2' => $qualified_num, 'column3' => $good_num, 'column4' => $good_rate, 'accident' => $accident, 'score' => $score, 'name' => $level3_name, 'quality' => $level3_quality, 'level' => $level]);

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


}