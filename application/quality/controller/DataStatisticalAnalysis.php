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
     *  开挖工程
     *  开挖工程需要统计的信息数据分为超挖、欠挖、不平整度和半孔率4类
     *  逐级进行统计分析，即单元工程统计该单元下所有单元工程检验批的信息数据，分部工程统计该分部工程下所有单元工程的信息数据，以此列推
     *  平均值 （cm）=该统计项目下平均超挖之和/该统计项目下单元工程验收批数。
     *  检测点数（个）=该统计项目下所有检测点之和。
     *  最大值max（cm）=该统计项目下所有值中取最大值。
     *  最小值min（cm）=该统计项目下所有值中取最小值。
     *  合格率Ps（%）=该统计项目下所有合格率的平均值。
     *  半孔率（%）=该统计项目下所有半孔率的平均值
     */
    public function excavate()
    {
        // 前台需要传递 的是 节点的主键 id
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['id', 'require|number|gt:-1', '请选择要查询的工程编号|工程编号只能是数字|工程编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            // 根据节点的主键 获取节点的属性(是单位,分部还是单元工程)
            $id = $param['id'];
            $pic = new AnchorPointModel();
            $flag = $pic->deleteTb($id);
            return json($flag);
        }
    }

    // 支护工程
    public function support()
    {

    }

    // 混凝土工程
    public function concrete()
    {

    }

    // 排水孔
    public function scupper()
    {

    }

}