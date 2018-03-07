<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:53
 */
//法规标准识别
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\StatutestdiModel;

class Statutestdi extends Base
{
    public  function  index()
    {
        return $this ->fetch();
    }

    /**
     * 修改
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiEdit()
    {
        if(request()->isAjax()){
            $sdi = new StatutestdiModel();
            $param = input('post.');
            $data = [
                'id' => $param['aid'],
                'sdi_number' => $param['sdi_number'],
                'sdi_name' => $param['sdi_name'],
                'go_date' => $param['go_date'],
                'standard' => $param['standard'],
                'evaluation' => $param['evaluation'],
                'sid_user' => $param['sid_user'],
                'remark' => $param['remark']
            ];
            $flag = $sdi->editSdi($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $sdi = new StatutestdiModel();
        $param = $sdi->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['sdi_name'];
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
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function sdiDel()
    {
        if(request()->isAjax()) {
            $sdi = new StatutestdiModel();
            $param = input('post.');
            $data = $sdi->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); //删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); //删除生成的预览pdf
            }
            $flag = $sdi->delSdi($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 预览
     * @return \think\response\Json
     * @author hutao
     */
    public function anualPreview()
    {
        $sdi = new StatutestdiModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $sdi->getOne($param['id']);
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