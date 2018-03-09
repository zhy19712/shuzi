<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 10:53
 */
namespace app\safety\controller;

use app\admin\controller\Base;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use app\safety\model\SafetySdiNodeModel;
use app\safety\model\StatutestdiModel;
//法规标准识别
class Statutestdi extends Base
{
    public  function  index()
    {
        if(request()->isAjax()){
            $node = new SafetySdiNodeModel();
            $nodeStr = $node->getNodeInfo(1);
            return json($nodeStr);
        }
        return $this ->fetch();
    }

    /**
     *  从组织机构及用户树中选择负责人
     * @return \think\response\Json
     * @author hutao
     */
    public function getSiduser()
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
    public function sdiEdit()
    {
        if(request()->isAjax()){
            $sdi = new StatutestdiModel();
            $param = input('post.');
            $data = [
                'id' => $param['id'],
                'group_id' => $param['group_id'],
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
        $id = input('post.id');
        $sdi = new StatutestdiModel();
        $param = $sdi->getOne($id);
        $filePath = $param['path'];
//        $fileName = $param['sdi_name'];
        $fileName = $param['sdi_name'] . '.' . substr(strrchr($filePath, '.'), 1); ;
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

    /**
     * 获取路径
     * @return \think\response\Json
     * @author hutao
     */
    public function getParents()
    {
        $node = new SafetySdiNodeModel();
        $parent = array();
        $path = "";
        if(request()->isAjax()){
            $param = input('post.');
            $id = $param['id'];
            while($id>0)
            {
                $data = $node->getOneNode($id);
                array_unshift($parent, $data['id']);
                $path = $data['pname'] . ">>" . $path;
                $id = $data['pid'];
            }
            return json(['path' => substr($path, 0 , -2), 'idList' => $parent, 'msg' => "success", 'code'=>1]);
        }
    }

    /**
     * 添加节点
     * @return \think\response\Json
     * @author hutao
     */
    public function nodeAdd()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $node = new SafetySdiNodeModel();
            $param['ptype'] = 1; // 1 法规标准识别 2 规章制度
            if(empty($param['id'])){
                $flag = $node->insertSdinode($param);
            }else if(!empty($param['id'])){
                $flag = $node->editSdinode($param);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 删除节点
     * @return \think\response\Json
     * @author hutao
     */
    public function nodeDel()
    {
        if(request()->isAjax()){
            $id = input('post.id');
            $node = new SafetySdiNodeModel();
            /**
             * 删除节点时，先判断该节点下是否包含子节点
             * 1，删除子节点下的所有文件
             * 2，删除子节点下
             * 3，删除该节点下的所有文件
             * 4，删除该节点
             */
            $idarr = $node->hasSubclass($id);
            if(count($idarr) > 0){
                foreach($idarr as $v){
                    $flag = $node->delSdinode($v,1); // 1 法规标准识别 2 规章制度
                    if($flag['code'] != 1){
                        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                    }
                }
            }
            $flag = $node->delSdinode($id,1);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}