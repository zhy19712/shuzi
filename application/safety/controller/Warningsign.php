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
            $warn = new WarningsignModel();
            $param = input('post.');
            $data = $warn->getOne($param['id']);
            //文件名、图片名、文件路径，图片路径不为空时进行拆解
            if(!empty($data['filename']))
            {
                $data['filename'] = explode("☆",$data['filename']);//拆解拼接的文件、图片名
            }else
            {
                $data['filename'] = array();//为空时返回一个空数组
            }

            if(!empty($data['path']))
            {
                $data['path'] = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            }else
            {
                $data['path'] = array();//为空时返回一个空数组
            }

            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
      * [warningsignEdit 警示标志新增/编辑]
     */
    public function warningsignEdit()
    {
        $warn = new WarningsignModel();
        $param = input('post.');

        $pathImgName = input('post.pathImgName/a');//获取post传过来的多个文件、图片的名字，包含在一个一维数组中。
        $pathImgArr = input('post.pathImgArr/a');//获取post传过来的多个文件、图片的路径，包含在一个一维数组中。
        $pathImgDel = input('post.pathImgDel/a');//获取post传过来要删除的多个文件、图片的路径，包含在一个一维数组中。

        //判断文件名、图片名、路径是否为空，为空的时候不拼接

        if(!empty($pathImgName))
        {
            $pathImgName = implode("☆",$pathImgName);//上传所有文件图片的拼接名
        }
        if(!empty($pathImgArr))
        {
            $pathImgArr = implode("☆",$pathImgArr);//上传所有文件、图片拼接路径

        }

        //循环删除文件、图片
        foreach((array)$pathImgDel as $v)
        {
            if(file_exists($v)){
                unlink($v); //删除上传的文件、路径
            }
        }

        if(request()->isAjax()){
            if(empty($param['id']))//id为空时表示新增
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'standard_number' =>$param['standard_number'],//标准号
                    'warn_name' =>$param['warn_name'],//名称
                    'structure_size' =>$param['structure_size'],//结构尺寸
                    'measurement_unit' =>$param['measurement_unit'],//结构尺寸

                    'filename' => $pathImgName,//拼接文件名、图片名

                    'path' => $pathImgArr,//拼接文件路径、图片路径

                    'date' => date("Y-m-d H:i:s"),//添加时间
                    'remark' => $param['remark']//备注
                ];
                $flag = $warn->insertWarningsign($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else
            {
                $data = [
//                    'id' => $param['id'],
//                    'selfid' => $param['selfid'],//区别类别
                    'standard_number' =>$param['standard_number'],//标准号
                    'warn_name' =>$param['warn_name'],//名称
                    'structure_size' =>$param['structure_size'],//结构尺寸
                    'measurement_unit' =>$param['measurement_unit'],//结构尺寸

                    'filename' => $pathImgName,//拼接文件名、图片名

                    'path' => $pathImgArr,//拼接文件路径、图片路径

//                    'date' => date("Y-m-d H:i:s"),//添加时间
                    'remark' => $param['remark']//备注
                ];
                $flag = $warn->editWarningsign($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

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

            if(!empty($data['path']))
            {
                $path = explode("☆",$data['path']);//拆解拼接的文件、图片路径
            }

            foreach ((array)$path as $v)
            {
                unlink($v); //删除文件、图片
            }
            $flag = $warn->delWarningsign($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}