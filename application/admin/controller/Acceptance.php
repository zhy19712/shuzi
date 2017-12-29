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
     * [保存验收批次信息]
     * @return [type] [description]
     */
    public function dataAdd()
    {
        $kaiwa = new KaiwaModel();
        $zhihu = new ZhihuModel();
        $hunningtu = new HunningtuModel();
        $maogan = new MaoganModel();

        $param = input('post.');
        if(request()->isAjax()){

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

            return $data;
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
        //此处给出你下载的文件名
        $file = fopen($filePath . $fileName, "r"); //   打开文件
        //输入文件标签
        Header("Content-type:application/octet-stream ");
        Header("Accept-Ranges:bytes ");
        Header("Accept-Length:   " . filesize($filePath . $fileName));
        Header("Content-Disposition:   attachment;   filename= " . $fileName);

        //   输出文件内容
        echo fread($file, filesize($filePath . $fileName));
        fclose($file);
        exit;
    }





}