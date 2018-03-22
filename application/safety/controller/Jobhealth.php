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
use app\safety\model\JobhealthManageModel;

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
     * [jobhealthEdit 职业健康的文件编辑]
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
     * [jobhealthDownload 下载一条职业健康的上传文件]
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


    /**
     * 预览获取一条数据  或者  编辑获取一条数据
     * [healthmanageindex 预览获取一条数据  或者  编辑获取一条数据的上传文件]
     * @return mixed|\think\response\Json
     */
    public function healthmanageindex()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $healthmanage = new JobhealthManageModel();
            $data = $healthmanage->getOne($param['id']);
            return json($data);
        }
        return $this ->fetch();
    }

    /**
     * 新增或者修改
     * [healthmanageAdd 新增或者修改]
     * @return \think\response\Json
     */
    public function healthmanageAdd()
    {
        if(request()->isAjax()){
            $healthmanage = new JobhealthManageModel();
            $param = input('post.');

            $harm_filename = $param['harm_filename'];
            $harm_path = $param['harm_path'];
            $healthy_filename = $param['healthy_filename'];
            $healthy_path = $param['healthy_path'];

            if(empty($param['id'])){

                $data = [

                    'fullname' => $param['fullname'],//姓名
                    'sex' => $param['sex'],//性别
                    'age' => $param['age'],//年龄
                    'station' => $param['station'],//岗位
                    'date' => date("Y-m-d H:i:s"),//添加时间
                    'remark' => $param['remark'],//备注
                    'harm_filename' => $harm_filename,//职业危害告知书文件名
                    'harm_path' => $harm_path,//职业危害告知书文件上传路径
                    'healthy_filename' => $healthy_filename,//职业健康体检报告
                    'healthy_path' => $healthy_path//职业健康体检报告文件上传路径
                ];
                $flag = $healthmanage->insertJobhealthManage($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else{
                //删除之前的文件路径
                $data_health = $healthmanage->getOne($param['id']);
                if(!empty($data_health['harm_path']))
                {
                    unlink($data_health['harm_path']);
                }

                if(!empty($data_health['healthy_path']))
                {
                    unlink($data_health['healthy_path']);
                }

                $data = [
                    'id' => $param['id'],
                    'fullname' => $param['fullname'],//姓名
                    'sex' => $param['sex'],//性别
                    'age' => $param['age'],//年龄
                    'station' => $param['station'],//岗位
//                    'date' => date("Y-m-d H:i:s"),//添加时间
                    'remark' => $param['remark'],//备注
                    'harm_filename' => $harm_filename,//职业危害告知书文件名
                    'harm_path' => $harm_path,//职业危害告知书文件上传路径
                    'healthy_filename' => $healthy_filename,//职业健康体检报告
                    'healthy_path' => $healthy_path//职业健康体检报告文件上传路径
                ];
                $flag = $healthmanage->editJobhealthManage($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /**
     * 删除
     * [healthmanageDel 删除]
     * @return \think\response\Json
     */
    public function healthmanageDel()
    {
        if(request()->isAjax()){
            $healthmanage = new JobhealthManageModel();
            $param = input('param.');
            //删除之前的文件路径
            $data_health = $healthmanage->getOne($param['id']);

            $harm_path = './uploads/temp/' . basename($data_health['harm_path']) . '.pdf';
            if(file_exists($harm_path)){
                unlink($harm_path); //删除生成的预览pdf
            }

            $healthy_path = './uploads/temp/' . basename($data_health['healthy_path']) . '.pdf';
            if(file_exists($healthy_path)){
                unlink($healthy_path); //删除生成的预览pdf
            }

            if(!empty($data_health['harm_path']))
            {
                unlink($data_health['harm_path']);
            }

            if(!empty($data_health['healthy_path']))
            {
                unlink($data_health['healthy_path']);
            }

            $flag = $healthmanage->delJobhealthManage($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * [harmpathPreview 职业危害告知书文件上传路径]
     */

    public function harmpathPreview()
    {
        $healthmanage = new JobhealthManageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $healthmanage->getOne($param['id']);
            $path = $data['harm_path'];
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
     * [healthypathPreview 职业健康体检报告文件上传路径]
     */

    public function healthypathPreview()
    {
        $healthmanage = new JobhealthManageModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $healthmanage->getOne($param['id']);
            $path = $data['healthy_path'];
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

}