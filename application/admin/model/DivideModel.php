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
    public function getNodeInfo_4($level = 0)
    {
        $str = "";
        if($level == 2){
            $pid = $this->where(['pid'=>0])->column('id');
            array_push($pid,0);
            $result = $this->where(['pid'=>['in',$pid]])->field('id,name,pid')->select();
        }else if($level == 3){
            $pid = $this->where(['pid'=>0])->column('id');
            $new_pid = $this->where(['pid'=>['in',$pid]])->column('id');
            array_push($new_pid,0);
            $result = $this->where(['pid'=>['in',$new_pid]])->whereOr(['id'=>['in',$new_pid]])->field('id,name,pid')->select();
        }else{
            $result = $this->field('id,name,pid')->select();
        }


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
        // 喷砼厚度==>支护面积,检测组数,设计值,最大值,最小值,平均值,合格率
        $supporting_area_1 = $thickness_number_1 = $design_val_1 = $max_1 = $min_1 = $avg_1 = $percent_1 = [];
        $count_num_1 = 0; // 该统计项目下单元工程验收批数(即该项目下所有最小子项之和)
        // 喷砼强度==>方量,设计等级,标准差,保证率
        $square_quantity_1 = $intensity_level_1 = $mortar_standard_deviation_1 = $guarantee_rate_1 = [];
        // 锚杆砂浆强度==>设计等级,标准差,保证率
        $intensity_level_2 = $mortar_standard_deviation_2 = $guarantee_rate_2 = [];
        // 锚杆无损检测==>施工数量(个),抽检根数(个),合格根数（个）,锚杆型号,锚杆长度-最大,锚杆长度-最小，注浆密实度-最大， 注浆密实度-最小
        $nde_quantity = $nde_inspection_num = $nde_percent_num = $nde_model_number = $nde_max = $nde_min = $nde_density_max = $nde_density_min = [];
        // 锚杆拉拔实验==>锚杆类型,设计拉拔力,检测根数（个）
        $anchor_type = $drawing_load = $experiment_inspection_num = [];

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
                $data_1 = $this->getDesign(1,$v['id'],1);
                $mortar_standard_deviation_1[] = $data_1['design_val'];
                $data_2 = $this->getDesign(1,$v['id'],2);
                $mortar_standard_deviation_2[] = $data_2['design_val'];
                // 保证率
                $guarantee_rate_1[] = $data_1['guarantee_rate_1'];
                $guarantee_rate_2[] = $data_2['guarantee_rate_1'];

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
    public function getDesign($genre,$id,$type)
    {
        $design_val = $f = $m = $n = $guarantee_rate_1 = 0;
        // genre 1 支护 2 混凝土
        $design = Db::name('project_standard_deviation')->where(['genre'=>$genre,'gid'=>['in',$id],'type'=>$type])->column('design_val');
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


    /**
     * 混凝土工程
     *  控制标准（℃）=该统计项目下所录入的控制标准（℃）（有几个控制标准，就显示几个控制标准，并且按不同控制标准分开显示）。
     *  检测组数（个）=该统计项目下所有检测组之和。
     *  合格组数（个）=该统计项目下所有合格组数之和。
     *  最大值=该统计项目下所有值中取最大值。
     *  最小值=该统计项目下所有值中取最小值。
     *  平均值=该统计项目下所有平均值之和/该统计项目下单元工程验收批数（即该项目下所有最小子项之和）。
     *  合格率Ps（%）=该统计项目下所有合格率的平均值。
     *  测次=该统计项目下所有测次之和
     *  合格次数（个）=该统计项目下所有合格次数之和。
     *  检测次数（个）=该统计项目下所有检测次数之和。
     *  设计指标（Mpa）=该统计项目下所录入的设计指标（有几个设计指标，就显示几个设计指标，并且按不同设计指标分开显示）
     *  龄期（d）=该统计项目下所录入的龄期（有几种龄期，就显示几种龄期，并且按不同对应的设计指标分开显示）。
     *  标准差、保证率参照2.1.2。
     *  取样组数=该统计项目下所有取样组数之和。
     *  测值=该统计项目下所录入的测值（有几种测值，就显示几种测值，并且按不同对应的设计指标分开显示）。
     *  测点数（个）=该统计项目下所有测点数之和。
     *  偏差范围（mm）=该统计项目下所有值中取最小值和最大值为偏差范围上下限。
     *  设计值=该统计项目下所录入的设计值（有几个设计值，就显示几个设计值，并且按不同设计值分开显示）。
     * @param $id
     * @param $cate
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author hutao
     */
    public function concrete($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的混凝土 信息
        $id_arr = Db::name('project_hunningtu')->where(['uid'=>['in',$unit_id]])->column('id');
        $h_data = Db::name('project_hnt_attachment')->where(['hid'=>['in',$id_arr]])->select();
        if(sizeof($h_data) < 1){
            return ['code'=>1,'excavate_data'=>[],'msg'=>'支护统计数据 -- 数据为空'];
        }
        // unit_type 1 施工单位 2 监理单位
        $data['builder'] = $this->getConcrete($h_data,1);
        $data['supervision_unit'] = $this->getConcrete($h_data,2);

        return ['code'=>1,'excavate_data'=>$data,'msg'=>'混凝土统计数据'];
    }

    public function getConcrete($h_data,$type)
    {
        // (出口机)==》控制标准 == 检测组数（个）== 合格组数（个）== 最大值 == 最小值 == 平均值 == 合格率Ps
        $data = $ex_control_criterion = $ex_test_groups = $ex_qualified_groups = $ex_max = $ex_min = $ex_avg = $ex_pass = [];
        // (入仓)==》测次 == 最大值 == 最小值 == 平均值 == 合格率Ps == 合格次数（个）
        $be_measurement = $be_max = $be_min = $be_avg = $be_pass = $be_num = [];
        // (浇筑)==》测次 == 最大值 == 最小值 == 平均值 == 合格率Ps == 合格次数（个）
        $pouring_measurement = $pouring_max = $pouring_min = $pouring_avg = $pouring_pass = $pouring_num = [];
        // (拌和物)==》设计指标 == 检测次数（个）== 合格次数（个）== 最大值 == 最小值 == 平均值 == 合格率Ps
        $mix_design = $mix_num = $mix_qualified_num = $mix_max = $mix_min = $mix_avg = $mix_pass = [];
        // (抗压强度)==》设计指标 == 龄期 == 检查组数 == 最大值 == 最小值 == 平均值 == 喷砼强度标准差 == 喷砼强度保证率 == 锚杆砂浆强度标准差 == 锚杆砂浆强度保证率
        $resist_design_index = $resist_age = $resist_test_group = $resist_max = $resist_min = $resist_avg = $mortar_standard_deviation_1 = $guarantee_rate_1 = $mortar_standard_deviation_2 = $guarantee_rate_2 = [];
        // (全面性能)==》设计指标 == 龄期 == (抗冻)取样组数 == (抗冻)测值 == (抗冻)合格率 == (抗渗)取样组数 == (抗渗)测值 == (抗渗)合格率
        $etc_design_index = $etc_age = $etc_anti_groups = $etc_anti_test = $etc_anti_pass = $etc_impervious_groups = $etc_impervious_test = $etc_impervious_pass = [];
        // (形体偏差)==》(平面)测点数(个) == (平面)偏差范围 == (平面)合格率 == (竖面)测点数(个) == (竖面)偏差范围 == (竖面)合格率
        $deviation_plane_num = $deviation_plane_scope = $deviation_plane_pass = $deviation_vertical_num = $deviation_vertical_scope = $deviation_vertical_pass = [];
        foreach ($h_data as $v) {
            if($v['unit_type'] == $type) {
                $ex_control_criterion[] = $v['ex_control_criterion'];
                $ex_test_groups[] = $v['ex_test_groups'];
                $ex_qualified_groups[] = $v['ex_qualified_groups'];
                $ex_max[] = $v['ex_max'];
                $ex_min[] = $v['ex_min'];
                $ex_avg[] = $v['ex_avg'];
                $ex_pass[] = $v['ex_pass'];

                $be_measurement[] = $v['be_measurement'];
                $be_max[] = $v['be_max'];
                $be_min[] = $v['be_min'];
                $be_avg[] = $v['be_avg'];
                $be_pass[] = $v['be_pass'];
                $be_num[] = $v['be_num'];

                $pouring_measurement[] = $v['pouring_measurement'];
                $pouring_max[] = $v['pouring_max'];
                $pouring_min[] = $v['pouring_min'];
                $pouring_avg[] = $v['pouring_avg'];
                $pouring_pass[] = $v['pouring_pass'];
                $pouring_num[] = $v['pouring_num'];

                $mix_design[] = $v['mix_design'];
                $mix_num[] = $v['mix_num'];
                $mix_qualified_num[] = $v['mix_qualified_num'];
                $mix_max[] = $v['mix_max'];
                $mix_min[] = $v['mix_min'];
                $mix_avg[] = $v['mix_avg'];
                $mix_pass[] = $v['mix_pass'];

                $resist_design_index[] = $v['resist_design_index'];
                $resist_age[] = $v['resist_age'];
                $resist_test_group[] = $v['resist_test_group'];
                $resist_max[] = $v['resist_max'];
                $resist_min[] = $v['resist_min'];
                $resist_avg[] = $v['resist_avg'];

                // 标准差 type 1喷砼强度2锚杆砂浆强度
                $data_1 = $this->getDesign(2,$v['id'],1);
                $mortar_standard_deviation_1[] = $data_1['design_val'];
                $data_2 = $this->getDesign(2,$v['id'],2);
                $mortar_standard_deviation_2[] = $data_2['design_val'];
                // 保证率
                $guarantee_rate_1[] = $data_1['guarantee_rate_1'];
                $guarantee_rate_2[] = $data_2['guarantee_rate_1'];

                $etc_design_index[] = $v['etc_design_index'];
                $etc_age[] = $v['etc_age'];
                $etc_anti_groups[] = $v['etc_anti_groups'];
                $etc_anti_test[] = $v['etc_anti_test'];
                $etc_anti_pass[] = $v['etc_anti_pass'];
                $etc_impervious_groups[] = $v['etc_impervious_groups'];
                $etc_impervious_test[] = $v['etc_impervious_test'];
                $etc_impervious_pass[] = $v['etc_impervious_pass'];

                $deviation_plane_num[] = $v['deviation_plane_num'];
                $deviation_plane_scope[] = $v['deviation_plane_scope'];
                $deviation_plane_pass[] = $v['deviation_plane_pass'];
                $deviation_vertical_num[] = $v['deviation_vertical_num'];
                $deviation_vertical_scope[] = $v['deviation_vertical_scope'];
                $deviation_vertical_pass[] = $v['deviation_vertical_pass'];

            }
        }
        if(sizeof($ex_avg) < 1){
            return $data;
        }
        // (出口机)
        $data['ex_control_criterion'] = $ex_control_criterion; // 控制标准
        $data['ex_test_groups'] = array_sum($ex_test_groups); // 检测组数（个）
        $data['ex_qualified_groups'] = array_sum($ex_qualified_groups); // 合格组数（个）
        $data['ex_max'] = max($ex_max); // 最大值
        $data['ex_min'] = min($ex_min); // 最小值
        $data['ex_avg'] = round(array_sum($ex_avg) / sizeof($ex_avg),2); // 平均值
        $data['ex_pass'] = round(array_sum($ex_pass) / sizeof($ex_pass),2); // 合格率Ps
        // (入仓)
        $data['be_measurement'] = array_sum($be_measurement); // 测次
        $data['be_max'] = max($be_max); // 最大值
        $data['be_min'] = min($be_min); // 最小值
        $data['be_avg'] = round(array_sum($be_avg) / sizeof($be_avg),2); // 平均值
        $data['be_pass'] = round(array_sum($be_pass) / sizeof($be_pass),2); // 合格率Ps
        $data['be_num'] = array_sum($be_num); // 合格次数（个）
        // (浇筑)
        $data['pouring_measurement'] = array_sum($pouring_measurement); // 测次
        $data['pouring_max'] = max($pouring_max); // 最大值
        $data['pouring_min'] = min($pouring_min); // 最小值
        $data['pouring_avg'] = round(array_sum($pouring_avg) / sizeof($pouring_avg),2); // 平均值
        $data['pouring_pass'] = round(array_sum($pouring_pass) / sizeof($pouring_pass),2); // 合格率Ps
        $data['pouring_num'] = array_sum($pouring_num); // 合格次数（个）

        // (拌和物)
        $data['mix_design'] = array_sum($mix_design); // 设计指标
        $data['mix_num'] = array_sum($mix_num); // 检测次数
        $data['mix_qualified_num'] = array_sum($mix_qualified_num); // 合格次数
        $data['mix_max'] = max($mix_max); // 最大值
        $data['mix_min'] = min($mix_min); // 最小值
        $data['mix_avg'] = round(array_sum($mix_avg) / sizeof($mix_avg),2); // 平均值
        $data['mix_pass'] = round(array_sum($mix_pass) / sizeof($mix_pass),2); // 合格率Ps

        // (抗压强度)
        $data['resist_design_index'] = $resist_design_index; // 设计指标
        $data['resist_age'] = $resist_age; // 龄期
        $data['resist_test_group'] = $resist_test_group; // 检查组数 == 最大值 == 最小值 == 平均值
        $data['resist_max'] = max($resist_max); // 最大值
        $data['resist_min'] = min($resist_min); // 最小值
        $data['resist_avg'] = round(array_sum($resist_avg) / sizeof($resist_avg),2); // 平均值
        $data['mortar_standard_deviation_1'] = $mortar_standard_deviation_1; // 喷砼强度 -- 标准差
        $data['guarantee_rate_1'] = $guarantee_rate_1; // 喷砼强度 -- 保证率
        $data['mortar_standard_deviation_2'] = $mortar_standard_deviation_2; // 锚杆砂浆强度 -- 标准差
        $data['guarantee_rate_2'] = $guarantee_rate_2; // 锚杆砂浆强度 -- 保证率

        // (全面性能)
        $data['etc_design_index'] = $etc_design_index; // 设计指标
        $data['etc_age'] = $etc_age; // 龄期
        $data['etc_anti_groups'] = array_sum($etc_anti_groups); // (抗冻)取样组数
        $data['etc_anti_test'] = $etc_anti_test; // (抗冻)测值
        $data['etc_anti_pass'] = round(array_sum($etc_anti_pass) / sizeof($etc_anti_pass),2); // (抗冻)合格率
        $data['etc_impervious_groups'] = array_sum($etc_impervious_groups); // (抗渗)取样组数
        $data['etc_impervious_test'] = $etc_impervious_test; // (抗渗)测值
        $data['etc_impervious_pass'] = round(array_sum($etc_impervious_pass) / sizeof($etc_impervious_pass),2); // (抗渗)合格率

        // (形体偏差)
        $data['deviation_plane_num'] = array_sum($deviation_plane_num); // (平面)测点数(个)
        $data['deviation_plane_scope'] = [min($deviation_plane_scope),max($deviation_plane_scope)]; // (平面)偏差范围
        $data['deviation_plane_pass'] = round(array_sum($deviation_plane_pass) / sizeof($deviation_plane_pass),2); // (平面)合格率
        $data['deviation_plane_num'] = array_sum($deviation_vertical_num); // (竖面)测点数(个)
        $data['deviation_vertical_scope'] = [min($deviation_vertical_scope),max($deviation_vertical_scope)]; // (竖面)偏差范围
        $data['deviation_vertical_pass'] = round(array_sum($deviation_vertical_pass) / sizeof($deviation_vertical_pass),2); // (竖面)合格率

        return $data;
    }

    // 排水孔

    /**
     * 设计孔深（m）=该统计项目下所录入的设计孔深（有几个设计孔深，就显示几个设计孔深，并且按不同设计孔深分开显示）。
     * 抽检数量=该统计项目下所有抽检数量之和
     * 平均值=该统计项目下所有平均值之和/该统计项目下单元工程验收批数（即该项目下所有最小子项之和）
     * 合格率=该统计项目下所有合格率的平均值。
     * @param $id
     * @param $cate
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author hutao
     */
    public function scupper($id,$cate)
    {
        $unit_id = $this->projectIdArr($id,$cate);
        // 根据 单元工程检验批 获取 所有的排水孔 信息
        $scupper_data = Db::name('project_scupper')->where(['divide_id'=>['in',$unit_id]])->select();
        if(sizeof($scupper_data) < 1){
            return ['code'=>1,'excavate_data'=>[],'msg'=>'排水孔统计数据 -- 数据为空'];
        }
        // 设计孔深,抽检数量,孔深平均值,孔深合格率,孔位平均值,孔位合格率,孔径平均值,孔径合格率,孔斜平均值,孔斜合格率
        $design_hole_depth = $sampling_quantity = $hole_depth_avg = $hole_depth_percent = $hole_site_avg = $hole_site_percent = $aperture_avg = $aperture_percent = $pore_slant_avg = $pore_slant_percent = [];
        foreach ($scupper_data as $v){
            $design_hole_depth[] = $v['design_hole_depth'];
            $sampling_quantity[] = $v['sampling_quantity'];
            $hole_depth_avg[] = $v['hole_depth_avg'];
            $hole_depth_percent[] = $v['hole_depth_percent'];
            $hole_site_avg[] = $v['hole_site_avg'];
            $hole_site_percent[] = $v['hole_site_percent'];
            $aperture_avg[] = $v['aperture_avg'];
            $aperture_percent[] = $v['aperture_percent'];
            $pore_slant_avg[] = $v['pore_slant_avg'];
            $pore_slant_percent[] = $v['pore_slant_percent'];
        }

        $data['design_hole_depth'] = $design_hole_depth; // 设计孔深
        $data['sampling_quantity'] = array_sum($sampling_quantity); // 抽检数量
        $data['hole_depth_avg'] = round(array_sum($hole_depth_avg) / sizeof(array_sum($hole_depth_avg)),2); // 孔深平均值
        $data['hole_depth_percent'] = round(array_sum($hole_depth_percent) / sizeof(array_sum($hole_depth_percent)),2); // 孔深合格率
        $data['hole_site_avg'] = round(array_sum($hole_site_avg) / sizeof(array_sum($hole_site_avg)),2); // 孔位平均值
        $data['hole_site_percent'] = round(array_sum($hole_site_percent) / sizeof(array_sum($hole_site_percent)),2); // 孔位合格率
        $data['aperture_avg'] = round(array_sum($aperture_avg) / sizeof(array_sum($aperture_avg)),2); // 孔径平均值
        $data['aperture_percent'] = round(array_sum($aperture_percent) / sizeof(array_sum($aperture_percent)),2); // 孔径合格率
        $data['pore_slant_avg'] = round(array_sum($pore_slant_avg) / sizeof(array_sum($pore_slant_avg)),2); // 孔斜平均值
        $data['pore_slant_percent'] = round(array_sum($pore_slant_percent) / sizeof(array_sum($pore_slant_percent)),2); // 孔斜合格率
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'排水孔统计数据'];
    }



}