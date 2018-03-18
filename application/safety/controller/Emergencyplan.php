<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/16
 * Time: 15:42
 */
//应急预案
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\EmergencyplanModel;
use app\safety\model\EmergencyreviseModel;
use think\Db;

class Emergencyplan extends Base
{
    /*
     * 获取一条应急预案信息
     */
    public function index()
    {
        if(request()->isAjax()){
            $emergency= new EmergencyplanModel();
            $param = input('post.');
            $data = $emergency->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     *修订一条应急预案信息
     */
    public function reviseEdit()
    {
        $emergency = new EmergencyplanModel();
        $revise = new EmergencyreviseModel();
        $param = input('post.');

        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/emergencyplan');

            if(request()->isAjax())
            {
                if($info)
                {
                    $temp = $info->getSaveName();
                    $path = './uploads/safety/emergencyplan/' . str_replace("\\","/",$temp);
                    $filename = $file->getInfo('name');

                    if(!empty($param['panid']))//有文件上传,设定一个变量panid表示有没有文件上传，panid=1表示有文件上传，empty为空为真，表示没有文件上传
                    {
                        $data_older = $emergency->getOne($param['aid']);
                        unlink($data_older['path']);
                        $data = [
                            'id' => $param['aid'],
                            'preplan_file_name' => $param['preplan_file_name'],
                            'preplan_number' => $param['preplan_number'],
                            'version_number' => $param['version_number'],
                            'alternative_version' => $param['alternative_version'],
                            'preplan_state' => $param['preplan_state'],
                            'name' => $filename,
                            'filename' => $filename,
                            'owner' => session('username'),
                            'date' => date("Y-m-d H:i:s"),
                            'path' => $path,
                            'remark' => $param['remark']
                        ];

                        $data1 = [
                            //                'id' => $param['aid'],
                            'preplan_file_name' => $param['preplan_file_name'],
                            'preplan_number' => $param['preplan_number'],
                            'version_number' => $param['version_number'],
                            'alternative_version' => $param['alternative_version'],
                            'preplan_state' => $param['preplan_state'],
                            'name' => $filename,
                            'filename' => $filename,
                            'owner' => session('username'),
                            'date' => date("Y-m-d H:i:s"),
                            'path' => $param['path'],
                            'remark' => $param['remark']
                        ];

                        $flag = $emergency->editEmergencyplan($data);
                        $flag1 = $revise->insertEmergencyrevise($data1);
                        if($flag['code'] && $flag1['code'])
                        {
                            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                        }
                    }
                }else{
                    if(empty($param['panid']))//没有文件上传
                    {
                        $data = [
                            'id' => $param['aid'],
                            'preplan_file_name' => $param['preplan_file_name'],
                            'preplan_number' => $param['preplan_number'],
                            'version_number' => $param['version_number'],
                            'alternative_version' => $param['alternative_version'],
                            'preplan_state' => $param['preplan_state'],
                            'remark' => $param['remark']
                        ];
                        $flag = $emergency->editEmergencyplan($data);
                        $data1 = [
//                            'id' => $param['aid'],
                            'preplan_file_name' => $param['preplan_file_name'],
                            'preplan_number' => $param['preplan_number'],
                            'version_number' => $param['version_number'],
                            'alternative_version' => $param['alternative_version'],
                            'preplan_state' => $param['preplan_state'],
                            'remark' => $param['remark']
                        ];
                        $flag1 = $revise->insertEmergencyrevise($data1);

                        if($flag['code'] && $flag1['code'])
                        {
                            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                        }
                    }
                }
            }

    }

    /*
     *下载一条应急预案信息
    */
    public function emergencyDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $emergency = new EmergencyplanModel();
        $param = $emergency->getOne($id);
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
     *删除一条应急预案信息
    */
    public function emergencyDel()
    {
        $emergency = new EmergencyplanModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $emergency->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $emergency->delEmergencyplan($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /*
     *预览一条应急预案信息
    */
    public function emergencyPreview()
    {
        $emergency = new EmergencyplanModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $emergency->getOne($param['id']);
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