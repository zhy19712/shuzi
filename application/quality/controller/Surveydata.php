<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/5/4
 * Time: 10:59
 */

namespace app\quality\controller;


use app\admin\controller\Base;
use app\quality\model\SurveyDataModel;

/**
 * 测量资料
 * Class Materialfile
 * @package app\quality\controller
 */
class Surveydata extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 获取一条编辑数据信息
     * @return \think\response\Json
     * @author hutao
     */
    public function getOne()
    {
        if(request()->isAjax()){
            $sdi = new SurveyDataModel();
            $data = $sdi->getOne(input('param.major_key'));
            return json($data);
        }
    }

    /**
     * 无文件上传编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function edit()
    {
        if(request()->isAjax()){
            $sdi = new SurveyDataModel();
            $param = input('post.');
            $is_exist = $sdi->getOne($param['major_key']);
            if(empty($is_exist)){
                return json(['code' => '-1', 'msg' => '不存在的编号，请刷新当前页面']);
            }
            $data = [
                'id' => $param['major_key'],
                'k_check_single_number' => $param['k_check_single_number'],
                'k_reception_time' => $param['k_reception_time'],
                'h_check_single_number' => $param['h_check_single_number'],
                'h_reception_time' => $param['h_reception_time'],
            ];
            $flag = $sdi->editSurvey($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function download()
    {
        $major_key = input('param.major_key');
        $sdi = new SurveyDataModel();
        $param = $sdi->getOne($major_key);
        $filePath = $param['path'];
        if(!file_exists($filePath)){
            return json(['code' => '-1','msg' => '文件不存在']);
        }else if(request()->isAjax()){
            return json(['code' => 1]); // 文件存在，告诉前台可以执行下载
        }else{
            $fileName = $param['filename'];
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

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function del()
    {
        if(request()->isAjax()) {
            $sdi = new SurveyDataModel();
            $param = input('param.');
            $flag = $sdi->delSurvey($param['major_key']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

}