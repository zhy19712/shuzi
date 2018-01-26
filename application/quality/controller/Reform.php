<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:35
 */

namespace app\quality\controller;

use app\admin\controller\Base;
use app\quality\model\ReformAttachmentModel;
use app\quality\model\ReformModel;

class Reform extends Base
{
    public function index()
    {
        $reform = new ReformModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $reform->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    public function ReformAdd()
    {
        $Reform = new ReformModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $Reform->insertReform($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $Reform->editReform($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function ReformDel()
    {
        $Reform = new ReformModel();
        if(request()->isAjax()){
            $param = input('post.');
            /**
             * 关联删除
             * think_reform_attachment
             */
            $reAtt = new ReformAttachmentModel();
            $att = $reAtt->delAttachmentByGroupId($param['id']);
            if($att['code'] == 0){
                return json($att);
            }

            $flag = $Reform->delReform($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function attachmentDownload()
    {
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $id = input('param.id');
        $attachment = new ReformAttachmentModel();
        $param = $attachment->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['name'] . '.' . substr(strrchr($filePath, '.'), 1); ;
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

    public function attachmentDel()
    {
        $attachment = new ReformAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $attachment->delAttachment($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function attachmentPreview()
    {
        $attachment = new ReformAttachmentModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $attachment->getOne($param['id']);
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