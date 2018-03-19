<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 13:58
 */
//应急演练方案
//应急演练影像资料
//应急演练
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EmergencyschemeModel;
use app\safety\model\EmergencyimagedataModel;
use app\safety\model\EmergencyrehearsalModel;

class Emergencyrehearsal extends Base
{
    /*
     * 获取一条应急演练信息
     */
    public function index()
    {
        if(request()->isAjax()){
            $emergencyscheme = new EmergencyschemeModel();
            $param = input('post.');
            $data = $emergencyscheme->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
    /*
     * 编辑一条应急演练信息
     */
    public function emergencyschemeEdit()
    {
        $emergencyscheme = new EmergencyschemeModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'number' =>$param['number'],
                'remark' => $param['remark']
            ];
            $flag = $emergencyscheme->editEmergencyrehearsalscheme($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     * 下载一条应急演练信息
     */
    public function emergencyschemeDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $emergencyscheme = new EmergencyschemeModel();
        $param = $emergencyscheme->getOne($id);
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
     * 删除一条应急演练信息
     */
    public function emergencyschemeDel()
    {
        $emergencyscheme = new EmergencyschemeModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $emergencyscheme->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $emergencyscheme->delEmergencyrehearsalscheme($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     * 预览一条应急演练信息
     */
    public function emergencyschemePreview()
    {
        $emergencyscheme = new EmergencyschemeModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $emergencyscheme->getOne($param['id']);
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




    /*
     * 获取一条应急演练影像资料信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $emergencyimagedata = new EmergencyimagedataModel();
            $param = input('post.');
            $data = $emergencyimagedata->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
    /*
     *编辑一条应急演练影像资料信息
     */
    public function emergencyimagedataEdit()
    {
        $emergencyimagedata = new EmergencyimagedataModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'place' =>$param['place'],
                'remark' => $param['remark']
            ];
            $flag = $emergencyimagedata->editEmergencyimagedata($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     *下载一条应急演练影像资料信息
     */
    public function emergencyimagedataDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $emergencyimagedata = new EmergencyimagedataModel();
        $param = $emergencyimagedata->getOne($id);
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
     *删除一条应急演练影像资料信息
     */
    public function emergencyimagedataDel()
    {
        $emergencyimagedata = new EmergencyimagedataModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $emergencyimagedata->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $emergencyimagedata->delEmergencyimagedata($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     *预览一条应急演练影像资料信息
     */
    public function emergencyimagedataPreview()
    {
        $emergencyimagedata = new EmergencyimagedataModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $emergencyimagedata->getOne($param['id']);
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



    /*
     * 获取一条应急演练信息
     */
    public function getoneindex()
    {
        if(request()->isAjax()){
            $emergencyrehearsal = new EmergencyrehearsalModel();
            $param = input('post.');
            $data = $emergencyrehearsal->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
    /*
     * 编辑一条应急演练信息
     */
    public function cultureEdit()
    {
        $emergencyrehearsal = new EmergencyrehearsalModel();
        $param = input('post.');
        if(request()->isAjax()){
            $data = [
                'id' => $param['aid'],
                'number' => $param['number'],
                'remark' => $param['remark']
            ];
            $flag = $emergencyrehearsal->editEmergencyrehearsal($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     * 下载一条应急演练信息
     */
    public function cultureDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $emergencyrehearsal = new EmergencyrehearsalModel();
        $param = $emergencyrehearsal->getOne($id);
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
     * 删除一条应急演练信息
     */
    public function cultureDel()
    {
        $emergencyrehearsal = new EmergencyrehearsalModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $emergencyrehearsal->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $emergencyrehearsal->delEmergencyrehearsal($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
    /*
     * 预览一条应急演练信息
     */
    public function culturePreview()
    {
        $emergencyrehearsal = new EmergencyrehearsalModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $emergencyrehearsal->getOne($param['id']);
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




}

