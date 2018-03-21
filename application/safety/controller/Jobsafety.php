<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 16:23
 */
//现场管理->作业安全
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\JobsafetyModel;
use app\safety\model\ViolationrecordModel;

class Jobsafety extends Base
{
    /*
     * 作业安全左边的树状结构
     */
    public function index()
    {
        if(request()->isAjax()){
            $node = new JobsafetyModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);
        }
        else
            return $this->fetch();
    }

    /*
     * 获取一条反违章记录信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $violationrecord = new ViolationrecordModel();
            $param = input('post.');
            $data = $violationrecord->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条反违章记录信息
     */
    public function  violationrecordEdit()
    {
        $violationrecord = new ViolationrecordModel();
        $param = input('post.');
        if(request()->isAjax()){
            if(empty($param['id']))//id为空时表示新增
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'full_name' => $param['full_name'],//姓名
                    'violation_situation' => $param['violation_situation'],//违章情况
                    'category_violation' => $param['category_violation'],//违章类别
                    'violation_time' => $param['violation_time'],//违章时间
                    'scoring_situation' => $param['scoring_situation'],//记分情况
                    'economic_punishment' => $param['economic_punishment'],//经济处罚
                    'disposal_results' => $param['disposal_results'],//处置结果
                    'rectify_rectify' => $param['rectify_rectify'],//整改情况
                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $violationrecord->insertViolationrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else
            {
                $data = [
                    'id' => $param['id'],
                    'full_name' => $param['full_name'],//姓名
                    'violation_situation' => $param['violation_situation'],//违章情况
                    'category_violation' => $param['category_violation'],//违章类别
                    'violation_time' => $param['violation_time'],//违章时间
                    'scoring_situation' => $param['scoring_situation'],//记分情况
                    'economic_punishment' => $param['economic_punishment'],//经济处罚
                    'disposal_results' => $param['disposal_results'],//处置结果
                    'rectify_rectify' => $param['rectify_rectify'],//整改情况
//                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $violationrecord->editViolationrecord($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条反违章记录信息
     */
    public function violationrecordDel()
    {
        $violationrecord = new ViolationrecordModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $flag = $violationrecord->delViolationrecord($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}