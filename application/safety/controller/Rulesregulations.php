<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 9:57
 */
namespace app\safety\controller;

use app\admin\controller\Base;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\safety\model\RulesregulationsModel;
use app\safety\model\SafetySdiNodeModel;

// 规章制度
class Rulesregulations extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $node = new SafetySdiNodeModel();
            $nodeStr = $node->getNodeInfo(2);
            return json($nodeStr);
        }
        return $this ->fetch();
    }

    /**
     *  从组织机构及用户树中选择负责人
     * @return \think\response\Json
     * @author hutao
     */
    public function getRuluser()
    {
        if(request()->isAjax()){
            $node1 = new UserType();
            $node2 = new UserModel();
            $nodeStr1 = $node1->getNodeInfo_1();
            $nodeStr2 = $node2->getNodeInfo_2();
            $nodeStr = "[" . substr($nodeStr1 . $nodeStr2, 0, -1) . "]";
            return json($nodeStr);
        }
    }

    /**
     * 修改
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesEdit()
    {
        if(request()->isAjax()){
            $rules = new RulesregulationsModel();
            $param = input('post.');
            $data = [
                'id' => $param['id'],
                'number' => $param['number'],
                'rul_name' => $param['rul_name'],
                'go_date' => $param['go_date'],
                'standard' => $param['standard'],
                'evaluation' => $param['evaluation'],
                'rul_user' => $param['rul_user'],
                'remark' => $param['remark']
            ];
            $flag = $rules->editRulation($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesDownload()
    {
        if(request()->isAjax()){
            return json(['code'=>1]);
        }
        $id = input('param.id');
        $rules = new RulesregulationsModel();
        $param = $rules->getOne($id);
        $filePath = $param['path'];
        $fileName = $param['rul_name'];
        $file = fopen($filePath, "r"); // 打开文件
        // 输入文件标签
        $fileName = iconv("utf-8","gb2312",$fileName);
        Header("Content-type:application/octet-stream ");
        Header("Accept-Ranges:bytes ");
        Header("Accept-Length:   " . filesize($filePath));
        Header("Content-Disposition:   attachment;   filename= " . $fileName);
        // 输出文件内容
        echo fread($file, filesize($filePath));
        fclose($file);
        exit;
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesDel()
    {
        if(request()->isAjax()){
            $rules = new RulesregulationsModel();
            $param = input('post.');
            $data = $rules->getOne($param['id']);
            $path = $data['path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if(file_exists($path)){
                unlink($path); // 删除文件
            }
            if(file_exists($pdf_path)){
                unlink($pdf_path); // 删除生成的预览pdf
            }
            $flag = $rules->delRulation($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function rulesPreview()
    {
        if(request()->isAjax()){
            $rules = new RulesregulationsModel();
            $param = input('post.');
            $code = 1;
            $msg = '预览成功';
            $data = $rules->getOne($param['id']);
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