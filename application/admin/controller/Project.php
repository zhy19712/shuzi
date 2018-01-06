<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/13
 * Time: 8:53
 */

namespace app\admin\controller;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;
use think\Db;

class project extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $node = new DivideModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);}
        else
            return $this->fetch();
    }


    /**
     * [projectAdd 单元工程验收批次添加信息(保存按钮)]
     */
    public function projectAdd()
    {
        $project = new ProjectModel();
        $param = input('post.');
        if(request()->isAjax()){

            if(empty($param['id']))
            {

                $flag = $project->insertProject($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $project->editProject($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
        return $this->fetch();
    }

    /**
     * [projectEdit 单元工程验收批次编辑]
     * @return [type] [description]
     */
    public function projectEdit()
    {
        $project = new ProjectModel();

        if(request()->isAjax()){

            $param = input('post.');
            $data = $project->getOneProject($param['id']);
            return json(['data' => $data, 'msg' => "success"]);
        }
    }

    /**
     * [projectDel 单元工程验收批次删除]
     * @return [type] [description]
     */
    public function projectDel()
    {
        $id = input('param.id');
        $project = new ProjectModel();
        $flag = $project->delProject($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    /**
     * [projectAdd 节点添加(保存按钮)]
     */
    public function nodeAdd()
    {
        $node = new DivideModel();
        $param = input('post.');
        if(request()->isAjax()){

            if(empty($param['id']))
            {

                $flag = $node->insertNode($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $node->editNode($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
        return $this->fetch();
    }

    /**
     * [projectEdit 节点编辑]
     * @return [type] [description]
     */
    public function nodeEdit()
    {
        $node = new DivideModel();

        if(request()->isAjax()){

            $param = input('post.');
            $data = $node->getOneNode($param['id']);
            return json(['data' => $data, 'msg' => "success"]);
        }
    }

    /**
     * [projectDel 节点删除]
     * @return [type] [description]
     */
    public function nodeDel()
    {
        $id = input('param.id');
        $node = new DivideModel();
        $project = new ProjectModel();

        $childList = $node->cateTree($id);
        foreach ($childList as $child){
            $node->delNode($child['id']);
        }
        $flag = $node->delNode($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [获取当前节点的所有父级]
     * @return [type] [description]
     */
    public function getParents()
    {
        $node = new DivideModel();
        $parent = array();
        $path = "";
        if(request()->isAjax()){
            $param = input('post.');
            $id = $param['id'];

            while($id>0)
            {
                $data = $node->getOneNode($id);
                array_unshift($parent, $data['id']);
                $path = $data['name'] . ">>" . $path;
                $id = $data['pid'];
                $data = array();
            }
            return json(['path' => substr($path, 0 , -2), 'idList' => $parent, 'msg' => "success"]);
        }
    }







}