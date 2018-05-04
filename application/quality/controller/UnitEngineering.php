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
            $nodeStr1 = $node1->getNodeInfo_4(3);
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
        if(request()->isAjax()){
            $param = input('post.');
            $projectData = $project->getOneProject($param['uid']);
            $p = urldecode(urldecode($param['cate']));
            if($p==="开挖")
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
                acceptanceWarning();//启动时刷新验收预警
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