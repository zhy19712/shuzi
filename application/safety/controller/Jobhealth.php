<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:23
 */
//职业健康
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\JobhealthModel;
use app\safety\model\JobhealthGroupModel;

class Jobhealth extends Base
{
    /*
     * [index 职业健康中的左边的分类节点]
    */
    public function index()
    {
        if(request()->isAjax()){
            $node = new JobhealthModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);

        }
        else
            return $this->fetch();
    }

    /*
     * [getindex 获取一条职业健康文件上传信息]
    */
    public function getindex()
    {
        if(request()->isAjax()){
            $insty= new JobhealthGroupModel();
            $param = input('post.');
            $data = $insty->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /**
     * [jobhealthPreview 职业健康检查中的文件预览]
     */

    public function jobhealthPreview()
    {
        $jobhealth = new JobhealthGroupModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $jobhealth->getOne($param['id']);
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
     * [jobhealthEdit 设置机构中的文件编辑]
     */
    public function jobhealthEdit()
    {
        $jobhealth = new JobhealthGroupModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['id'],
                'filenumber' => $param['filenumber'],
                'checktime'=>$param['checktime'],
                'remark'=>$param['remark']
            ];
            $flag = $jobhealth->editJobhealth($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * [jobhealthDel 设置机构中的文件删除]
     */
    public function jobhealthDel()
    {
        $jobhealth = new JobhealthGroupModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $jobhealth->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $jobhealth->delJobhealth($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * [responsibilityinstyDownload 下载一条设置机构中的上传文件]
     */
    public function jobhealthDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $jobhealth = new JobhealthGroupModel();
        $param = $jobhealth->getOne($id);
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