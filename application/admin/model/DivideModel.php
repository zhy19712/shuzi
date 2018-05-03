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
        $child_node_id[] = $id;
        // 获取此节点下包含的所有单元工程检验批
        $unit_id = Db::name('project')->where(['pid'=>['in',$child_node_id],'cate'=>$cate])->column('id');
        return $unit_id;
    }

    /**
     *  开挖工程 -- 分为 明挖 和 洞挖
     *
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
        if(sizeof($excavate_data) < 1){
            return ['code'=>1,'excavate_data'=>[],'msg'=>'开挖统计数据 -- 数据为空'];
        }
        $data['open_cut'] = $this->getKaiWa($excavate_data,1); // type 1 明挖 2 洞挖
        $data['hole_digging'] = $this->getKaiWa($excavate_data,2);

        return ['code'=>1,'excavate_data'=>$data,'msg'=>'开挖统计数据'];
    }

    public function getKaiWa($excavate_data,$type)
    {
        $data = [];
        $ave_1 = $ave_2 = $ave_3 = []; // 平均超挖,平均欠挖,平均不平整度,平均半孔率
        $unit_batch = 0; // 单元工程验收批数
        $points_1 = $points_2 = $points_3 = []; // 超挖检测点数,欠挖检测点数,不平整度检测点数,半孔率检测点数,
        $max_1 = $max_2 = $max_3 = []; // 最大值
        $min_1 = $min_2 = $min_3 = []; // 最小值
        $percent_1 = $percent_2 = $percent_3 = []; // 合格率
        $half_1 = []; // 半孔率
        foreach($excavate_data as $v){
            // 1 明挖工程 2 洞挖工程
            if($type == $v['type']){
                $ave_1[] = $v['ave_overbreak'];
                $ave_2[] = $v['ave_underbreak'];
                $ave_3[] = $v['avg_irregularity_degree'];

                $unit_batch = $unit_batch + 1;

                $points_1[] = $v['points_overbreak'];
                $points_2[] = $v['points_underbreak'];
                $points_3[] = $v['points_irregularity_degree'];

                $max_1[] = $v['max_overbreak'];
                $max_2[] = $v['max_underbreak'];
                $max_3[] = $v['max_irregularity_degree'];

                $min_1[] = $v['min_overbreak'];
                $min_2[] = $v['min_underbreak'];
                $min_3[] = $v['min_irregularity_degree'];

                $percent_1[] = $v['pass_overbreak'];
                $percent_2[] = $v['pass_underbreak'];
                $percent_3[] = $v['pass_irregularity_degree'];

                $half_1[] = $v['half_percentage'];
            }
        }

        // 超挖
        if($unit_batch == 0){
            return  $data;
        }
        $data['average_val'][] = round(array_sum($ave_1) / $unit_batch,2); // 平均值
        $data['max_val'][] = max($max_1); // 最大值
        $data['min_val'][] = min($min_1); // 最小值

        $data['detection_points'][] = array_sum($points_1); // 检测点数

        $data['percent_of_pass'][] = round(array_sum($percent_1) / sizeof($percent_1),2); // 合格率

        // 欠挖
        $data['average_val'][] = round(array_sum($ave_2) / $unit_batch,2); // 平均值
        $data['max_val'][] = max($max_2); // 最大值
        $data['min_val'][] = min($min_2); // 最小值

        $data['detection_points'][] = array_sum($points_2); // 检测点数

        $data['percent_of_pass'][] = round(array_sum($percent_2) / sizeof($percent_2),2); // 合格率

        // 不平整度
        $data['average_val'][] = round(array_sum($ave_3) / $unit_batch,2); // 平均值
        $data['max_val'][] = max($max_3); // 最大值
        $data['min_val'][] = min($min_3); // 最小值

        $data['detection_points'][] = array_sum($points_3); // 检测点数

        $data['percent_of_pass'][] = round(array_sum($percent_3) / sizeof($percent_3),2); // 合格率

        // 半孔率
        $data['half']['zhen_d'] = round(array_sum($half_1) / sizeof($half_1),2); // 半孔率
        // 这里存放的是 多余值 作用是  便于 前台饼图的 百分比划分
        $data['half']['jia_d'] = round($data['half']['zhen_d'] / 100,2); // 半孔率
        return  $data;
    }


    /**
     * 支护工程
     *
     * 支护工程需要统计的信息数据分为喷砼厚度、喷砼强度、锚杆砂浆强度、锚杆无损检测和锚杆拉拔试验5类，逐级进行统计分析，
     * 即单元工程统计该单元下所有单元工程检验批的信息数据，分部工程统计该分部工程下所有单元工程的信息数据，以此列推。
     *
     * 支护面积（m2）=该统计项目下所有支护面积之和。
     * 检测组数（个）=该统计项目下所有检测组之和。
     * 设计值 = 该统计项目下所录入的设计值（有几个设计值，就显示几个设计值，并且按不同设计值分开显示）。
     * 最大值max（cm）=该统计项目下所有值中取最大值。
     * 最小值min（cm）=该统计项目下所有值中取最小值。
     * 平均值 （cm）=该统计项目下所有平均值之和/该统计项目下单元工程验收批数（即该项目下所有最小子项之和）。
     * 合格率Ps（%）=该统计项目下所有合格率的平均值。
     * 方量（m3）=该统计项目下所有方量之和。
     * 设计等级=该统计项目下所录入的设计等级（有几个设计等级，就显示几个设计等级，并且按不同设计等级分开显示）。
     * 标准差（Mpa）=根据混凝土强度标准差计算公式进行自动计算，计算公式
     * 保证率（%）=根据混凝土强度保证率计算公式进行自动计算
     * 施工数量（个）=该统计项目下所有施工数量之和。
     * 抽检根数（个）=该统计项目下所有抽检根数之和。
     * 检测比例（%）=抽检根数/施工数量*100%
     * 合格根数（个）=该统计项目下所有合格根数之和
     * 锚杆型号=该统计项目下所录入的锚杆型号（有几种锚杆型号，就显示几种锚杆型号，并且按不同锚杆型号分开显示）。
     * 锚杆长度-最大（m）=该统计项目下所有值中取最大值
     * 锚杆长度-最小（m）=该统计项目下所有值中取最小值
     * 注浆密实度-最大（%）=该统计项目下所有值中取最大值
     * 注浆密实度-最小（%）=该统计项目下所有值中取最小值
     * 锚杆类型=锚杆型号
     * 设计拉拔力（KN）=该统计项目下所录入的设计拉拔力值（一种锚杆型号对应一个固定的设计拉拔力，有几种锚杆型号，就显示对应的设计拉拔力，并且按不同锚杆型号分开显示）。
     * 检测根数（个）=该统计项目下所有检测根数之和
     */
    public function support($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的支护 信息
        $id_arr = Db::name('project_zhihu')->where(['uid'=>['in',$unit_id]])->column('id');
        // 根据支护表获取关联的锚杆检测组
        $zhihu_test_group = Db::name('project_zhihu_test_group')->where(['uid'=>['in',$id_arr]])->select();
        if(sizeof($zhihu_test_group) < 1){
            return ['code'=>1,'excavate_data'=>[],'msg'=>'支护统计数据 -- 数据为空'];
        }
        // 1 施工单位 2 监理单位
        $data['builder'] = $this->getZhiHu($zhihu_test_group,1);
        $data['supervision_unit'] = $this->getZhiHu($zhihu_test_group,2);

        return ['code'=>1,'excavate_data'=>$data,'msg'=>'支护统计数据'];
    }

    public function getZhiHu($zhihu_test_group,$type)
    {
        $data = [];
        // 喷砼厚度
        $supporting_area_1 = []; // 支护面积
        $thickness_number_1 =[]; // 检测组数
        $design_val_1 = []; // 设计值
        $max_1 = []; // 最大值
        $min_1 = []; // 最小值
        $avg_1 = []; // 平均值
        $count_num_1 = 0; // 该统计项目下单元工程验收批数(即该项目下所有最小子项之和)
        $percent_1 = []; // 合格率

        // 喷砼强度
        $square_quantity_1 = []; // 方量
        $intensity_level_1 = []; // 设计等级
        $mortar_standard_deviation_1 = []; // 标准差
        $guarantee_rate_1 = []; // 保证率

        // 锚杆砂浆强度
        $intensity_level_2 = []; // 设计等级
        $mortar_standard_deviation_2 = []; // 标准差
        $guarantee_rate_2 = []; // 保证率

        // 锚杆无损检测
        $nde_quantity = []; // 施工数量(个)
        $nde_inspection_num = []; // 抽检根数(个)
        $detection_ratio = 0; // 检测比例
        $nde_percent_num = []; // 合格根数（个）
        $nde_model_number = []; // 锚杆型号
        $nde_max = []; // 锚杆长度-最大
        $nde_min = []; // 锚杆长度-最大
        $nde_density_max = []; // 锚杆长度-最小
        $nde_density_min = []; // 注浆密实度-最大

        // 锚杆拉拔实验
        $anchor_type = []; // 锚杆类型
        $drawing_load = []; // 设计拉拔力
        $experiment_inspection_num = []; // 检测根数（个）

        // unit_type 1 施工单位 2 监理单位
        foreach ($zhihu_test_group as $v){
            if($v['unit_type'] == $type){
                $supporting_area_1[] = $v['supporting_area'];
                $thickness_number_1[] = $v['thickness_number'];
                $design_val_1[] = $v['design_val'];
                $max_1[] = $v['max_val'];
                $min_1[] = $v['min_val'];
                $avg_1[] = $v['avg_val'];
                $percent_1[] = $v['pass_percentage'];

                $square_quantity_1[] = $v['square_quantity'];
                $intensity_level_1[] = $v['intensity_level'];

                $intensity_level_2[] = $v['mortar_level'];

                // 标准差 type 1喷砼强度2锚杆砂浆强度
                $data_1 = $this->getDesign($v['id'],1);
                $mortar_standard_deviation_1[] = $data_1['design_val'];
                $data_2 = $this->getDesign($v['id'],2);
                $mortar_standard_deviation_2[] = $data_2['design_val'];
                // 保证率
                $guarantee_rate_1 = $data_1['guarantee_rate_1'];
                $guarantee_rate_2 = $data_2['guarantee_rate_1'];

                $nde_quantity[] = $v['nde_quantity']; // 该统计项目下所有施工数量之和
                $nde_inspection_num[] = $v['nde_inspection_num']; // 该统计项目下所有抽检根数之和。
                $nde_percent_num[] = $v['nde_percent_num']; // 该统计项目下所有合格根数之和
                $nde_model_number[] = $v['nde_model_number']; // 该统计项目下所录入的锚杆型号
                $nde_max[] = $v['nde_max'];
                $nde_min[] = $v['nde_min'];
                $nde_density_max[] = $v['nde_density_max'];
                $nde_density_min[] = $v['nde_density_min'];

                $anchor_type[] = $v['anchor_type'];
                $drawing_load[] = $v['drawing_load'];
                $experiment_inspection_num[] = $v['experiment_inspection_num'];

                $count_num_1 = $count_num_1 + 1;
            }
        }

        if($count_num_1 == 0){
            return $data;
        }
        // 按照 设计值 分组 统计 ==》 设计值相同的统计到一起 (求平均值)
        $design_supporting_area = $design_thickness_number = $design_max = $design_min = $design_avg = $design_percent = $design_square_quantity = $design_intensity_level = $design_mortar_standard_deviation =[];
        $design_guarantee_rate = $design_mortar_standard_deviation2 = $design_guarantee_rate2 = $design_nde_quantity = $design_nde_inspection_num = $design_nde_percent_num = $design_nde_model_number = [];
        $design_nde_max = $design_nde_min = $design_nde_density_max = $design_nde_density_min = $design_anchor_type = $design_drawing_load = $design_experiment_inspection_num = [];
        $arr = array_count_values($design_val_1); // 每一个设计值出现的次数
        $arr_1 = array_keys($arr); // 相同的设计值
        foreach ($design_val_1 as $dk=>$dv){
            $design_supporting_area[$dv][] = $supporting_area_1[$dk];
            $design_thickness_number[$dv][] = $thickness_number_1[$dk];
            $design_max[$dv][] = $max_1[$dk];
            $design_min[$dv][] = $min_1[$dk];
            $design_avg[$dv][] = $avg_1[$dk];
            $design_percent[$dv][] = $percent_1[$dk];

            $design_square_quantity[$dv][] = $square_quantity_1[$dk];
            $design_intensity_level[$dv][] = $intensity_level_1[$dk];
            $design_mortar_standard_deviation[$dv][] = $mortar_standard_deviation_1[$dk];
            $design_guarantee_rate[$dv][] = $guarantee_rate_1[$dk];
            $design_mortar_standard_deviation2[$dv][] = $mortar_standard_deviation_2[$dk];
            $design_guarantee_rate2[$dv][] = $guarantee_rate_2[$dk];

            $design_nde_quantity[$dv][] = $nde_quantity[$dk];
            $design_nde_inspection_num[$dv][] = $nde_inspection_num[$dk];
            $design_nde_percent_num[$dv][] = $nde_percent_num[$dk];
            $design_nde_model_number[$dv][] = $nde_model_number[$dk];
            $design_nde_max[$dv][] = $nde_max[$dk];
            $design_nde_min[$dv][] = $nde_min[$dk];
            $design_nde_density_max[$dv][] = $nde_density_max[$dk];
            $design_nde_density_min[$dv][] = $nde_density_min[$dk];

            $design_anchor_type[$dv][] = $anchor_type[$dk];
            $design_drawing_load[$dv][] = $drawing_load[$dk];
            $design_experiment_inspection_num[$dv][] = $experiment_inspection_num[$dk];
        }


        foreach($arr_1 as $arv){
            $data['design_data']['design_val'][] = $arv; // 相同的设计值
            $data['design_data']['supporting_area'][] = array_sum($design_supporting_area[$arv]); // 支护面积
            $data['design_data']['thickness_number'][] = array_sum($design_thickness_number[$arv]); // 检测组数
            $data['design_data']['max'][] = max($design_max[$arv]); // 最大值
            $data['design_data']['min'][] = min($design_min[$arv]); // 最小值
            $data['design_data']['avg_val'][] = round(array_sum($design_avg[$arv]) / sizeof($design_avg[$arv]),2); // 平均值
            $data['design_data']['pass'][] = round(array_sum($design_percent[$arv]) / sizeof($design_percent[$arv]),2); // 合格率Ps

            $data['design_data']['square_quantity'][] = array_sum($design_square_quantity[$arv]); // 方量
            $data['design_data']['intensity_level'][] = $design_intensity_level[$arv]; // 设计等级
            $data['design_data']['mortar_standard_deviation_1'][] = $design_mortar_standard_deviation[$arv]; // 喷砼强度 -- 标准差
            $data['design_data']['guarantee_rate_1'][] = $design_guarantee_rate[$arv]; // 喷砼强度 -- 保证率
            $data['design_data']['mortar_standard_deviation_2'][] = $design_mortar_standard_deviation2[$arv]; // 锚杆砂浆强度 -- 标准差
            $data['design_data']['guarantee_rate_2'][] = $design_guarantee_rate2[$arv]; // 锚杆砂浆强度 -- 保证率

            $data['design_data']['nde_quantity'][] = array_sum($design_nde_quantity[$arv]); // 施工数量(个)
            $data['design_data']['nde_inspection_num'][] = array_sum($design_nde_inspection_num[$arv]); // 抽检根数(个)
            $data['design_data']['detection_ratio'][] = round(array_sum($design_nde_quantity[$arv]) / array_sum($design_nde_inspection_num[$arv]),2) * (100/100); // 检测比例
            $data['design_data']['nde_percent_num'][] = array_sum($design_nde_percent_num[$arv]); // 合格根数（个）
            $data['design_data']['nde_model_number'] = $design_nde_model_number[$arv]; // 锚杆型号
            $data['design_data']['nde_max'][] = max($design_nde_max[$arv]); // 锚杆长度-最大
            $data['design_data']['nde_min'][] = min($design_nde_min[$arv]); // 锚杆长度-最小
            $data['design_data']['nde_density_max'][] = max($design_nde_density_max[$arv]); // 注浆密实度-最大
            $data['design_data']['nde_density_min'][] = min($design_nde_density_min[$arv]); // 注浆密实度-最小

            $data['design_data']['anchor_type'][] = $design_anchor_type[$arv]; // 锚杆类型
            $data['design_data']['drawing_load'][] = $design_drawing_load[$arv]; // 设计拉拔力
            $data['design_data']['experiment_inspection_num'][] = array_sum($design_experiment_inspection_num[$arv]); // 检测根数（个）
        }



        $data['supporting_area'] = array_sum($supporting_area_1); // 支护面积
        $data['thickness_number'] = array_sum($thickness_number_1); // 检测组数
//        $data['design_val'] = $design_val_1; // 设计值
        $data['max'] = max($max_1); // 最大值
        $data['min'] = min($min_1); // 最小值
        $data['avg_val'] = round(array_sum($avg_1) / $count_num_1,2); // 平均值
        $data['pass'] = round(array_sum($percent_1) / $count_num_1,2); // 合格率Ps

        $data['square_quantity'] = array_sum($square_quantity_1); // 方量
        $data['intensity_level'] = $intensity_level_1; // 设计等级
        $data['mortar_standard_deviation_1'] = $mortar_standard_deviation_1; // 喷砼强度 -- 标准差
        $data['guarantee_rate_1'] = $guarantee_rate_1; // 喷砼强度 -- 保证率
        $data['mortar_standard_deviation_2'] = $mortar_standard_deviation_2; // 锚杆砂浆强度 -- 标准差
        $data['guarantee_rate_2'] = $guarantee_rate_2; // 锚杆砂浆强度 -- 保证率

        $data['nde_quantity'] = array_sum($nde_quantity); // 施工数量(个)
        $data['nde_inspection_num'] = array_sum($nde_inspection_num); // 抽检根数(个)
        $data['detection_ratio'] = round($data['nde_quantity'] / $data['nde_inspection_num'],2) * (100/100); // 检测比例
        $data['nde_percent_num'] = array_sum($nde_percent_num); // 合格根数（个）
        $data['nde_model_number'] = $nde_model_number; // 锚杆型号
        $data['nde_max'] = max($nde_max); // 锚杆长度-最大
        $data['nde_min'] = min($nde_min); // 锚杆长度-最小
        $data['nde_density_max'] = max($nde_density_max); // 注浆密实度-最大
        $data['nde_density_min'] = min($nde_density_min); // 注浆密实度-最小

        $data['anchor_type'] = $anchor_type; // 锚杆类型
        $data['drawing_load'] = $drawing_load; // 设计拉拔力
        $data['experiment_inspection_num'] = array_sum($experiment_inspection_num); // 检测根数（个）

        return $data;
    }

    // 计算标准差 和 百分率 保证率
    public function getDesign($id,$type)
    {
        $design_val = $f = $m = $n = $guarantee_rate_1 = 0;
        $design = Db::name('project_standard_deviation')->where(['gid'=>['in',$id],'type'=>$type])->column('design_val');
        if(sizeof($design) > 0){
            $f = round(array_sum($design) / sizeof($design),2);// 统计周期内第i组混凝土试件强度值
            $arr = array_count_values($design); // 每一组强度出现的次数
            foreach ($arr as $av){
                if($av > 1){
                    $n = $n + $av; // 统计周期内相同强度等级的混凝土试件组数 求 出现次数大于1的和
                }
            }
            $new_arr = array_keys($arr); // 所有相同的强度
            $m = round(array_sum($new_arr) / sizeof($new_arr),2); // 统计周期内n组混凝土试件的强度平均值
            $no = 0; // 统计周期内试件强度不低于要求强度等级值的组数
            foreach ($design as $d){
                if($d >= $f){
                    $no = $no + 1;
                }
            }
            $design_val = sqrt( ( pow($f,2) - $n * pow($m,2) ) / ($n-1) ); // 套用公式计算
            $percentage = ($no / $n) * (100/100); // 百分率
        }

        $guarantee_rate_arr = [65.5,69.2,72.5,75.8,78.8,80.0,82.9,85.0,90.0,93.3,95.0,97.7,99.9];
        $t_arr = [0.40,0.50,0.60,0.70,0.80,0.84,0.95,1.04,1.28,1.50,1.65,2.0,3.0];
        $t = round(($m - $f) / $design_val,1); // 概率度系数
        // 插值法计算 保证率
        $start_t = $end_t = 0;
        foreach($t_arr as $tk=>$tv){
            if($t < $tv){
                if($tk == 0){
                    $start_t = 0;
                    $val_1 = $guarantee_rate_arr[$tk];
                    $val_2 = 0;
                }else{
                    $start_t = $t_arr[$tk-1];
                    $val_1 = $guarantee_rate_arr[$tk] - $guarantee_rate_arr[$tk-1];
                    $val_2 = $guarantee_rate_arr[$tk-1];
                }
                $end_t = $tv;
                $guarantee_rate_1 = round(($val_1 * ($t - $start_t)) / ($end_t - $start_t),1) + $val_2;
                break;
            }
        }
        $data['design_val'] = $design_val;
        $data['guarantee_rate_1'] = $guarantee_rate_1;
        return $design_val;
    }

    // 混凝土工程
    public function concrete($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的混凝土 信息
        $excavate_data = Db::name('project_hunningtu')->where(['uid'=>['in',$unit_id]])->select();

        // (出口机)
        $ex_control_criterion = []; // 控制标准

        $data = [];
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'混凝土统计数据'];
    }

    // 排水孔
    public function scupper($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的排水孔 信息
        $excavate_data = Db::name('project_kaiwa')->where(['uid'=>['in',$unit_id]])->select();
        $data = [];
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'排水孔统计数据'];
    }



}