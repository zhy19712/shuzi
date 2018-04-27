<?php
/**
 * Created by PhpStorm.
 * User: zhangchuan
 * Date: 2017/12/18
 * Time: 13:59
 */

namespace app\admin\model;


use think\Db;
use think\exception\PDOException;
use think\Model;

class DivideModel extends Model
{
    protected  $name = 'project_divide';

    /**
     * [getNodeInfo 获取工程划分4级节点树结构数据]
     *
     */
    public function getNodeInfo()
    {
        $result = $this->field('id,name,pid')->select();
        $str = "";

        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['name'].'"';

            $str .= '},';
        }

        return "[" . substr($str, 0, -1) . "]";
    }

    /**
     * [getNodeInfo 获取工程划分5级节点树结构数据的前4级]
     *
     */
    public function getNodeInfo_4()
    {
        $result = $this->field('id,name,pid')->select();
        $str = "";

        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['name'].'"';

            $str .= '},';
        }

        return $str;
    }




    /**
     * 插入新的节点
     */
    public function insertNode($param)
    {
        try{
            $result = $this->validate('ProjectDivideValidate')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '节点添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑节点信息
     */
    public function editNode($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '信息编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [节点删除]
     */
    public function delNode($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '节点删除成功'];
    }

    /**
     * 根据id获取节点
     */
    public function getOneNode($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * [getAll 获取全部信息]
     */
    public function getAll()
    {
        return $this->order('id asc')->select();
    }

    //getAll by pid
    public function getAllbyPID($pid)
    {
        return $this->where('pid', $pid)->select();
    }
    //getAll by id
    public function getOnebyID($id)
    {
        return $this->where('id', $id)->find();
    }

    //get counts by id
    public function getNum($pid)
    {
        return $this->where('pid',$pid)->count();
    }
    //getAll by pid and primary
    public function getNumPrimary($pid)
    {
        $where['pid'] = $pid;
        $where['primary'] = '是';
        return $this->where($where)->count();
    }
    public function getQualifiedNum($pid)
    {
        $where['pid'] = $pid;
        $where['level'] = '合格';
        return $this->where($where)->count();
    }
    public function getQualifiedNumPrimary($pid)
    {
        $where['pid'] = $pid;
        $where['primary'] = '是';
        $where['level'] = '合格';
        return $this->where($where)->count();
    }
    public function getGoodNum($pid)
    {
        $where['pid'] = $pid;
        $where['level'] = '优良';
        return $this->where($where)->count();
    }
    public function getGoodNumPrimary($pid)
    {
        $where['pid'] = $pid;
        $where['primary'] = '是';
        $where['level'] = '优良';
        return $this->where($where)->count();
    }






    //递归获取当前节点的所有子节点
    public function cateTree($id){
        $res=$this->select();
        if($res){
            $result=$this->sort($res, $id);
            return $result;
        }
    }
    public function sort($data,$id,$level=0){
       static $arr=array();
        foreach ($data as $key=>$value){
            if($value['pid'] == $id){
                $value["level"]=$level;
                $arr[]=$value;
                $this->sort($data,$value['id'],$level+1);
            }
        }
        return $arr;
    }



    public function projectIdArr($id,$cate)
    {
        // 获取此节点下包含的所有子节点编号
        $child_node_id = [];
        $child_node_obj = $this->cateTree($id);
        foreach ($child_node_obj as $v){
            $child_node_id[] = $v['id'];
        }
        // 获取此节点下包含的所有单元工程检验批
        $unit_id = Db::name('project')->where(['pid'=>['in',$child_node_id],'cate'=>$cate])->column('id');
        return $unit_id;
    }

    /**
     *  开挖工程
     *  开挖工程需要统计的信息数据分为超挖、欠挖、不平整度和半孔率4类
     *  逐级进行统计分析，即单元工程统计该单元下所有单元工程检验批的信息数据，分部工程统计该分部工程下所有单元工程的信息数据，以此列推
     *
     *  平均值 （cm）=该统计项目下平均超挖之和/该统计项目下单元工程验收批数。
     *  检测点数（个）=该统计项目下所有检测点之和。
     *  最大值max（cm）=该统计项目下所有值中取最大值。
     *  最小值min（cm）=该统计项目下所有值中取最小值。
     *  合格率Ps（%）=该统计项目下所有合格率的平均值。
     *  半孔率（%）=该统计项目下所有半孔率的平均值
     * @author hutao
     */
    public function excavateData($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的开挖 信息
        $excavate_data = Db::name('project_kaiwa')->where(['uid'=>['in',$unit_id]])->select();
        $ave_1 = $ave_2 = $ave_3 = $ave_4 = []; // 平均超挖,平均欠挖,平均不平整度,平均半孔率
        $unit_batch = 0; // 单元工程验收批数
        $points_1 = $points_2 = $points_3 = $points_4 = []; // 超挖检测点数,欠挖检测点数,不平整度检测点数,半孔率检测点数,
        $max_1 = $max_2 = $max_3 = $max_4 = []; // 最大值
        $min_1 = $min_2 = $min_3 = $min_4 = []; // 最小值
        $percent_1 = $percent_2 = $percent_3 = $percent_4 = []; // 合格率
        $half_1 = $half_2 = $half_3 = $half_4 = []; // 半孔率
        foreach($excavate_data as $v){
            $ave_1[] = $v['ave_overbreak'];
            $ave_2[] = $v['ave_underbreak'];
            $ave_3[] = $v['avg_irregularity_degree'];
            $ave_4[] = $v['ave'];

            $unit_batch = $unit_batch + 1;

            $points_1[] = $v['points_overbreak'];
            $points_2[] = $v['points_underbreak'];
            $points_3[] = $v['points_irregularity_degree'];
            $points_4[] = $v['points'];

            $max_1[] = $v['max_overbreak'];
            $max_2[] = $v['max_underbreak'];
            $max_3[] = $v['max_irregularity_degree'];
            $max_4[] = $v['max'];

            $min_1[] = $v['min_overbreak'];
            $min_2[] = $v['min_underbreak'];
            $min_3[] = $v['min_irregularity_degree'];
            $min_4[] = $v['min'];

            $percent_1[] = $v['pass_overbreak'];
            $percent_2[] = $v['pass_underbreak'];
            $percent_3[] = $v['pass_irregularity_degree'];
            $percent_4[] = $v['half_percentage'];

            $half_1[] = $v['half_overbreak'];
            $half_2[] = $v['half_underbreak'];
            $half_3[] = $v['half_irregularity_degree'];
            $half_4[] = $v['half_percentage'];
        }
        // 超挖
        $data['back_break']['average_val'] = array_sum($ave_1) / $unit_batch; // 平均值
        $data['back_break']['detection_points'] = array_sum($points_1); // 检测点数
        $data['back_break']['max_val'] = max($max_1); // 最大值
        $data['back_break']['min_val'] = min($min_1); // 最小值
        $data['back_break']['percent_of_pass'] = array_sum($percent_1) / sizeof($percent_1); // 合格率
        $data['back_break']['half'] = array_sum($half_1) / sizeof($half_1); // 半孔率

        // 欠挖
        $data['under_break']['average_val'] = array_sum($ave_2) / $unit_batch; // 平均值
        $data['under_break']['detection_points'] = array_sum($points_2); // 检测点数
        $data['under_break']['max_val'] = max($max_2); // 最大值
        $data['under_break']['min_val'] = min($min_2); // 最小值
        $data['under_break']['percent_of_pass'] = array_sum($percent_2) / sizeof($percent_2); // 合格率
        $data['back_break']['half'] = array_sum($half_2) / sizeof($half_2); // 半孔率

        // 不平整度
        $data['irregularity_degree']['average_val'] = array_sum($ave_3) / $unit_batch; // 平均值
        $data['irregularity_degree']['detection_points'] = array_sum($points_3); // 检测点数
        $data['irregularity_degree']['max_val'] = max($max_3); // 最大值
        $data['irregularity_degree']['min_val'] = min($min_3); // 最小值
        $data['irregularity_degree']['percent_of_pass'] = array_sum($percent_3) / sizeof($percent_3); // 合格率
        $data['back_break']['half'] = array_sum($half_3) / sizeof($half_3); // 半孔率

        // 半孔率
        $data['half_porosity']['average_val'] = array_sum($ave_4) / $unit_batch; // 平均值
        $data['half_porosity']['detection_points'] = array_sum($points_4); // 检测点数
        $data['half_porosity']['max_val'] = max($max_4); // 最大值
        $data['half_porosity']['min_val'] = min($min_4); // 最小值
        $data['half_porosity']['percent_of_pass'] = array_sum($percent_4) / sizeof($percent_4); // 合格率
        $data['back_break']['half'] = array_sum($half_4) / sizeof($half_4); // 半孔率

        return ['code'=>1,'excavate_data'=>$data,'msg'=>'开挖统计数据'];
    }


    // 支护工程
    public function support($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的支护 信息
        $excavate_data = Db::name('project_zhihu')->where(['uid'=>['in',$unit_id]])->select();
        $data = [];
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'开挖统计数据'];
    }

    // 混凝土工程
    public function concrete($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的混凝土 信息
        $excavate_data = Db::name('project_hunningtu')->where(['uid'=>['in',$unit_id]])->select();
        $data = [];
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'开挖统计数据'];
    }

    // 排水孔
    public function scupper($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的排水孔 信息
        $excavate_data = Db::name('project_kaiwa')->where(['uid'=>['in',$unit_id]])->select();
        $data = [];
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'开挖统计数据'];
    }



}