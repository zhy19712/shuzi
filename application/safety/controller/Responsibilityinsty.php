<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 9:49
 */
//机构和职责
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\ResponsibilityinstyModel;
use app\safety\model\ResponsibilityinstyGroupModel;

class Responsibilityinsty extends Base
{

    /*
     * [index 设置机构中左边的分类节点]
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new ResponsibilityinstyModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);

        }
        else
            return $this->fetch();
    }

    /*
     * [getindex 获取一条设置机构文件上传信息]
    */
    public function getindex()
    {
        if(request()->isAjax()){
            $insty= new ResponsibilityinstyGroupModel();
            $param = input('post.');
            $data = $insty->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

     /**
     * [responsibilityinstyPreview 设置机构中的文件预览]
     */

    public function responsibilityinstyPreview()
    {
        $group = new ResponsibilityinstyGroupModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $group->getOne($param['id']);
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

    /**
     * [responsibilityinstyEdit 设置机构中的文件编辑]
     */
    public function responsibilityinstyEdit()
    {
        $group = new ResponsibilityinstyGroupModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'remark' => $param['remark'],
                'version'=>$param['version']
            ];
            $flag = $group->editResponsibilityinstyGroup($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * [responsibilityinstyDel 设置机构中的文件删除]
     */
    public function responsibilityinstyDel()
    {
        $id = input('param.id');
        $group = new ResponsibilityinstyGroupModel();
        $flag = $group->delResponsibilityinstyGroup($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [responsibilityinstyDownload 下载一条设置机构中的上传文件]
     */
    public function responsibilityinstyDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $group = new ResponsibilityinstyGroupModel();
        $param = $group->getOne($id);
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


}