<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\admin\controller;
use app\admin\model\ZhihuModel;
use think\Db;
use app\admin\model\AcceptanceModel;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;
use app\admin\model\KaiwaModel;
use app\admin\model\Hunningtu;


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
        $hunningtu = new Hunningtu();
        $zhihu = new ZhihuModel();
        if(request()->isAjax()){
            $param = input('post.');
            $projectData = $project->getOneProject($param['uid']);
            $kaiwaData = $kaiwa->getOne($param['uid']);
            $hunningtuData = $hunningtu->getOne($param['uid']);
            $zhihuData = $zhihu->getOne($param['uid']);
            return json(['projectData' => $projectData, 'kaiwaData' => $kaiwaData, 'hunningtuData' => $hunningtuData, 'zhihuData' => $zhihuData,'msg' => "success"]);
        }
    }


    /**
     * [保存验收批次信息]
     * @return [type] [description]
     */
    public function dataAdd()
    {
        $kaiwa = new KaiwaModel();
        $param = input('post.');
        if(request()->isAjax()){

            if(empty($param['id']))
            {
                $flag = $kaiwa->insert($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $kaiwa->edit($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
        return $this->fetch();
    }


}