<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\admin\controller;
use app\admin\model\MaoganModel;
use app\admin\model\ZhihuModel;
use think\Db;
use app\admin\model\AcceptanceModel;
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
                $flag = $kaiwa->insert($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='支护')
            {
                $flag = $zhihu->insert($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='混凝土')
            {
                $flag = $hunningtu->insert($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(empty($param['edit'])&&$param['cate']=='锚杆')
            {
                $flag = $maogan->insert($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='开挖')
            {
                $flag = $kaiwa->edit($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='支护')
            {
                $flag = $zhihu->edit($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['edit'])&&$param['cate']=='混凝土')
            {
                $flag = $hunningtu->edit($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else if(!empty($param['edit'])&&$param['cate']=='锚杆')
            {
                $flag = $maogan->edit($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
        return $this->fetch();
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
        if(request()->isAjax()){
            $param = input('post.');
            $uid = $param['uid'];
            $temp = $project->getOneProject($uid);
            $id = $temp['pid'];
            $path = $temp['name'];
            array_push($parent, $temp['uid']);
            unset($temp);
            while($id>0)
            {
                $data = $node->getOneNode($id);
                array_push($parent, $data['id']);
                $path = $data['name'] . ">>" . $path;
                $id = $data['pid'];
                $data = array();
            }
            return json(['path' => $path, 'idList' => $parent, 'msg' => "success"]);
        }
    }


}