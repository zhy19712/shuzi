<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:33
 */

namespace app\quality\controller;
use app\admin\controller\Base;
use app\admin\model\DivideModel;

/**
 *  数据统计分析
 * == 开挖工程
 * == 支护工程
 * == 混凝土工程
 * == 排水孔
 * Class DataStatisticalAnalysis
 * @package app\quality\controller
 * @author hutao
 */
class DataStatisticalAnalysis extends Base
{
    /**
     * 初始化页面 节点树
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function index()
    {
        if(request()->isAjax()){
            $node = new DivideModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);
        }
        return $this->fetch();
    }

    /**
     * 数据统计分析
     * @return \think\response\Json
     * @author hutao
     */
    public function excavate()
    {
        // 前台需要传递 的是 节点的主键 id 展示的 工程类型 cate 开挖,支护,混凝土,排水孔
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['id', 'require|number|gt:-1', '请选择要查询的工程编号|工程编号只能是数字|工程编号不能为负数'],
                ['cate', 'require', '请选择要查询的工程类型']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $pic = new DivideModel();
            // 获取开挖的统计分析数据
            if($param['cate'] == '开挖'){
                $excavate_data = $pic->excavateData($param['id'],$param['cate']);
            }else if($param['cate'] == '支护'){
                $excavate_data = $pic->support($param['id'],$param['cate']);
            }else if($param['cate'] == '混凝土'){
                $excavate_data = $pic->concrete($param['id'],$param['cate']);
            }else if($param['cate'] == '排水孔'){
                $excavate_data = $pic->scupper($param['id'],$param['cate']);
            }else{
                return json(['code'=>1,'excavate_data'=>array(),'msg'=>'开挖统计数据']);
            }
            return json($excavate_data);
        }
    }

}