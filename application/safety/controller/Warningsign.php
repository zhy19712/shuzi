<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/15
 * Time: 19:09
 */
//警示标志
namespace app\safety\controller;

use app\admin\controller\Base;
use think\Db;
use think\Loader;
use app\safety\model\WarningsignModel;

class Warningsign extends Base
{
    /*
     * [index 警示标志中左边的分类节点]
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new WarningsignModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);

        }
        else
            return $this->fetch();
    }

    /*
     * [getindex 获取一条警示标志图片信息]
    */
    public function getindex()
    {
        if(request()->isAjax()){
            $warn= new WarningsignModel();
            $param = input('post.');
            $data = $warn->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
      * [warningsignEdit 警示标志编辑]
     */
    public function warningsignEdit()
    {
        $warn = new WarningsignModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'standard_number' =>$param['standard_number'],//标准号
                'warn_name' =>$param['warn_name'],//名称
                'structure_size' =>$param['structure_size'],//结构尺寸
                'measurement_unit' =>$param['measurement_unit'],//结构尺寸
                'remark' => $param['remark']//备注
            ];
            $flag = $warn->editWarningsign($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     * [warningsignDel 警示标志删除]
    */
    public function warningsignDel()
    {
        $warn = new WarningsignModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $warn->getOne($param['id']);
            $path = $data['path'];
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            $flag = $warn->delWarningsign($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}