<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:11
 */
//现场管理->设备设施管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EquipmentModel;
use app\safety\model\EquipmentCheckAcceptModel;

class Equipmentmanage extends Base
{
    /*
     * 设备设施管理页面左边的树状结构
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new EquipmentModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);
        }
        else
            return $this->fetch();
    }

    /*
     * 获取一条设备设施验收信息
    */
    public function getindex()
    {
        if(request()->isAjax()){
            $equipment= new EquipmentCheckAcceptModel();
            $param = input('post.');
            $data = $equipment->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     *编辑一条设备设施验收信息
    */
    public function  equipmentEdit()
    {
        $equipment = new EquipmentCheckAcceptModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'remark' => $param['remark']
            ];
            $flag = $equipment->editEquipmentCheckAccept($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     *下载一条设备设施验收信息
    */
    public function equipmentDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $equipment = new EquipmentCheckAcceptModel();
        $param = $equipment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['name'];
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

    /*
     *删除一条设备设施验收信息
    */
    public function equipmentDel()
    {
        $equipment = new EquipmentCheckAcceptModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $equipment->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $equipment->delEquipmentCheckAccept($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}