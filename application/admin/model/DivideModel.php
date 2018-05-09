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
        $unit_id = Db::name('project')->where(['pid'=>['in',$child_node_id],'cate'=>['in',$cate]])->column('id');
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
        $unit_id = $this->projectIdArr($id,['开挖','明挖','洞挖']);
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
        $ave_1 = $ave_2 = $ave_3 = []; // [超挖,欠挖,不平整度] 平均值
        $unit_batch = 0; // 单元工程验收批数
        $points_1 = $points_2 = $points_3 = []; // [超挖,欠挖,不平整度] 检测点数
        $max_1 = $max_2 = $max_3 = []; // [超挖,欠挖,不平整度]  最大值
        $min_1 = $min_2 = $min_3 = []; // [超挖,欠挖,不平整度]  最小值
        $percent_1 = $percent_2 = $percent_3 = []; // [超挖,欠挖,不平整度] 合格率
        $half_1 = []; // 半孔率
        foreach($excavate_data as $v){
            if($type == $v['type']){ // 1 明挖工程 2 洞挖工程
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
        $data['max_val'][] = sizeof($max_1) ? max($max_1) : 0; // 最大值
        $data['min_val'][] = sizeof($min_1) ? min($min_1) : 0; // 最小值

        $data['detection_points'][] = array_sum($points_1); // 检测点数

        $percent_1 = array_filter($percent_1);
        if(sizeof($percent_1)){
            $data['percent_of_pass'][] = round(array_sum($percent_1) / sizeof($percent_1),2); // 合格率
        }else{
            $data['percent_of_pass'][] = 0; // 合格率
        }

        // 欠挖
        $data['average_val'][] = round(array_sum($ave_2) / $unit_batch,2); // 平均值
        $data['max_val'][] = sizeof($max_2) ? max($max_2) : 0; // 最大值
        $data['min_val'][] = sizeof($min_2) ? min($min_2) : 0; // 最小值

        $data['detection_points'][] = array_sum($points_2); // 检测点数

        $percent_2 = array_filter($percent_2);
        if(sizeof($percent_2)){
            $data['percent_of_pass'][] = round(array_sum($percent_2) / sizeof($percent_2),2); // 合格率
        }else{
            $data['percent_of_pass'][] = 0; // 合格率
        }

        // 不平整度
        $data['average_val'][] = round(array_sum($ave_3) / $unit_batch,2); // 平均值
        $data['max_val'][] = sizeof($max_3) ? max($max_3) : 0; // 最大值
        $data['min_val'][] = sizeof($max_3) ? min($min_3) : 0; // 最小值

        $data['detection_points'][] = array_sum($points_3); // 检测点数

        $percent_3 = array_filter($percent_3);
        if(sizeof($percent_3)){
            $data['percent_of_pass'][] = round(array_sum($percent_3) / sizeof($percent_3),2); // 合格率
        }else{
            $data['percent_of_pass'][] = 0;
        }

        // 半孔率
        $half_1 = array_filter($half_1);
        if(sizeof($percent_3)){
            $data['half']['zhen_d'] = round(array_sum($half_1) / sizeof($half_1),2); // 半孔率
            // 这里存放的是 多余值 作用是  便于 前台饼图的 百分比划分
            $data['half']['jia_d'] = round($data['half']['zhen_d'] / 100,2); // 半孔率
        }else{
            $data['half']['zhen_d'] = 0;
            $data['half']['jia_d'] = 100;
        }
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
        $unit_id = $this->projectIdArr($id,[$cate]);
        // 根据 单元工程检验批 获取 所有的支护 信息
        $id_arr = Db::name('project_zhihu')->where(['uid'=>['in',$unit_id]])->column('id');
        $zhihu = Db::name('project_zhihu')->where(['uid'=>['in',$unit_id]])->select();
        // 喷砼统计
        $data = [];
        // 喷砼厚度==>支护面积,检测组数,设计值,最大值,最小值,平均值,合格率
        $supporting_area_1 = $thickness_number_1 = $design_val_1 = $max_1 = $min_1 = $avg_1 = $percent_1 = [];
        $count_num_1 = $count_num_11 = 0; // 该统计项目下单元工程验收批数(即该项目下所有最小子项之和)
        // 喷砼强度==>方量,检测组数,设计等级,最大值,最小值,平均值,标准差,保证率,合格率
        $square_quantity_1 = $intensity_number_1 = $intensity_level_1 = $intensity_max_1 = $intensity_min_1 = $intensity_avg_1 = $mortar_standard_deviation_1 = $guarantee_rate_1 = $intensity_percent_1 = [];
        $square_quantity_2 = $intensity_number_2 = $intensity_level_2 = $intensity_max_2 = $intensity_min_2 = $intensity_avg_2 = $mortar_standard_deviation_2 = $guarantee_rate_2 = $intensity_percent_2 = [];
        foreach ($zhihu as $v){
            // 喷砼厚度
            $supporting_area_1[] = $v['supporting_area'];
            $thickness_number_1[] = $v['thickness_number'];
            $design_val_1[] = $v['design_val'];
            $max_1[] = $v['max_val'];
            $min_1[] = $v['min_val'];
            $avg_1[] = $v['avg_val'];
            $percent_1[] = $v['pass_percentage'];

            // 1 施工单位 -- 喷砼强度
            $square_quantity_1[] = $v['square_quantity'];
            $intensity_number_1[] = $v['intensity_number'];
            $intensity_level_1[] = $v['intensity_level'];
            $intensity_max_1[] = $v['intensity_max'];
            $intensity_min_1[] = $v['intensity_min'];
            $intensity_avg_1[] = $v['intensity_avg'];
            // 标准差,保证率
            // genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $data_1 = $this->getDesign(1, $v['id'],1,1);
            $mortar_standard_deviation_1[] = $data_1['design_val'];
            $guarantee_rate_1[] = $data_1['guarantee_rate_1'];
            $intensity_percent_1[] = $v['intensity_percent'];

            // 2 监理单位 -- 喷砼强度
            $square_quantity_2[] = $v['square_quantity_2'];
            $intensity_number_2[] = $v['intensity_number_2'];
            $intensity_level_2[] = $v['intensity_level_2'];
            $intensity_max_2[] = $v['intensity_max_2'];
            $intensity_min_2[] = $v['intensity_min_2'];
            $intensity_avg_2[] = $v['intensity_avg_2'];
            // genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $data_1 = $this->getDesign(1, $v['id'], 2,1);
            $mortar_standard_deviation_2[] = $data_1['design_val'];
            $guarantee_rate_2[] = $data_1['guarantee_rate_1'];
            $intensity_percent_2[] = $v['intensity_percent_2'];

            $count_num_1++;
        }

        /**
         * 喷砼厚度
         * 按不同设计值分开显示  分组 统计 ==》 设计值 相同的统计到一起 (求平均值)
         */
        $supporting_area_11 = $thickness_number_11 = $max_11 = $min_11 = $avg_11 = $percent_11 = [];
        $arr = array_count_values($design_val_1); // 每一个 设计值 出现的次数
        $arr_1 = array_keys($arr); // 相同的 设计值
        foreach ($design_val_1 as $dk=>$dv){
            $supporting_area_11[$dv][] = $supporting_area_1[$dk];
            $thickness_number_11[$dv][] = $thickness_number_1[$dk];
            $max_11[$dv][] = $max_1[$dk];
            $min_11[$dv][] = $min_1[$dk];
            $avg_11[$dv][] = $avg_1[$dk];
            $percent_11[$dv][] = $percent_1[$dk];
            $count_num_11++;
        }
        $data['zhihu_h'] = [];
        foreach($arr_1 as $arv){
            $data['zhihu_h']['ex_control_criterion'][] = $arv; // 相同的设计值
            $data['zhihu_h']['supporting_area'] = array_sum($supporting_area_11); // 支护面积
            $data['zhihu_h']['thickness_number'] = array_sum($thickness_number_11); // 检测组数
            $data['zhihu_h']['max'] = sizeof($max_11) ? max($max_11) : 0; // 最大值
            $data['zhihu_h']['min'] = sizeof($min_11) ? min($min_11) : 0; // 最小值
            if($count_num_11 == 0){
                $data['zhihu_h']['avg_val'] = 0; // 平均值
                $data['zhihu_h']['pass'] = 0; // 合格率Ps
            }else{
                $data['zhihu_h']['avg_val'] = round(array_sum($avg_11) / $count_num_11,2); // 平均值
                $data['zhihu_h']['pass'] = round(array_sum($percent_11) / $count_num_11,2); // 合格率Ps
            }
        }

        /**
         * 1 施工单位 -- 喷砼强度
         * 按不同设计等级分开显示  分组 统计 ==》 设计等级 相同的统计到一起 (求平均值)
         */
        $square_quantity_11 = $intensity_number_11 = $intensity_max_11 = $intensity_min_11 = $intensity_avg_11 = $mortar_standard_deviation_11 = $guarantee_rate_11 = $intensity_percent_11 = [];
        $arr2 = array_count_values($intensity_level_1); // 每一个 设计等级 出现的次数
        $arr_2 = array_keys($arr2); // 相同的 设计等级
        foreach ($intensity_level_1 as $dk=>$dv){
            $square_quantity_11[$dv][] = $square_quantity_1[$dk];
            $intensity_number_11[$dv][] = $intensity_number_1[$dk];
            $intensity_max_11[$dv][] = $intensity_max_1[$dk];
            $intensity_min_11[$dv][] = $intensity_min_1[$dk];
            $intensity_avg_11[$dv][] = $intensity_avg_1[$dk];
            $mortar_standard_deviation_11[$dv][] = $mortar_standard_deviation_1[$dk];
            $guarantee_rate_11[$dv][] = $guarantee_rate_1[$dk];
            $intensity_percent_11[$dv][] = $intensity_percent_1[$dk];
        }
        $data['zhihu_q'] = [];
        foreach($arr_2 as $arv){
            $data['zhihu_q']['ex_control_criterion'][] = $arv; // 相同的设计等级
            $data['zhihu_q']['square_quantity'] = array_sum($square_quantity_11); // 方量
            $data['zhihu_q']['intensity_number'] = array_sum($intensity_number_11); // 检测组数
            $data['zhihu_q']['intensity_max'] = sizeof($intensity_max_11) ? max($intensity_max_11) : 0; // 最大值
            $data['zhihu_q']['intensity_min'] = sizeof($intensity_min_11) ? min($intensity_min_11) : 0; // 最小值
            $intensity_avg_11 = array_filter($intensity_avg_11);
            if(sizeof($intensity_avg_11) == 0){
                $data['zhihu_q']['intensity_avg'] = 0; // 平均值
            }else{
                $data['zhihu_q']['intensity_avg'] = round(array_sum($intensity_avg_11) / sizeof($intensity_avg_11),2); // 平均值
            }
            $mortar_standard_deviation_11 = array_filter($mortar_standard_deviation_11);
            if(sizeof($mortar_standard_deviation_11) == 0){
                $data['zhihu_q']['mortar_standard_deviation'] = 0; // 标准差
            }else{
                $data['zhihu_q']['mortar_standard_deviation'] = round(array_sum($mortar_standard_deviation_11) / sizeof($mortar_standard_deviation_11),2); // 标准差
            }
            $guarantee_rate_11 = array_filter($guarantee_rate_11);
            if(sizeof($guarantee_rate_11) == 0){
                $data['zhihu_q']['mortar_standard_deviation'] = 0; // 保证率
            }else{
                $data['zhihu_q']['mortar_standard_deviation'] = round(array_sum($guarantee_rate_11) / sizeof($guarantee_rate_11),2); // 保证率
            }
            $intensity_percent_11 = array_filter($intensity_percent_11);
            if(sizeof($intensity_percent_11) == 0){
                $data['zhihu_q']['intensity_percent'] = 0; // 合格率Ps
            }else{
                $data['zhihu_q']['intensity_percent'] = round(array_sum($intensity_percent_11) / sizeof($intensity_percent_11),2); // 合格率Ps
            }
        }

        /**
         * 2 监理单位 -- 喷砼强度
         * 按不同设计等级分开显示  分组 统计 ==》 设计等级 相同的统计到一起 (求平均值)
         */
        $square_quantity_22 = $intensity_number_22 = $intensity_max_22 = $intensity_min_22 = $intensity_avg_22 = $mortar_standard_deviation_22 = $guarantee_rate_22 = $intensity_percent_22 = [];
        $arr3 = array_count_values($intensity_level_2); // 每一个 设计等级 出现的次数
        $arr_3 = array_keys($arr3); // 相同的 设计等级
        foreach ($intensity_level_2 as $dk=>$dv){
            $square_quantity_22[$dv][] = $square_quantity_2[$dk];
            $intensity_number_22[$dv][] = $intensity_number_2[$dk];
            $intensity_max_22[$dv][] = $intensity_max_2[$dk];
            $intensity_min_22[$dv][] = $intensity_min_2[$dk];
            $intensity_avg_22[$dv][] = $intensity_avg_2[$dk];
            $mortar_standard_deviation_22[$dv][] = $mortar_standard_deviation_2[$dk];
            $guarantee_rate_22[$dv][] = $guarantee_rate_2[$dk];
            $intensity_percent_22[$dv][] = $intensity_percent_2[$dk];
        }
        $data['zhihu_q2'] = [];
        foreach($arr_3 as $arv){
            $data['zhihu_q2']['ex_control_criterion_2'][] = $arv; // 相同的设计等级
            $data['zhihu_q2']['square_quantity_2'] = array_sum($square_quantity_22); // 方量
            $data['zhihu_q2']['intensity_number_2'] = array_sum($intensity_number_22); // 检测组数
            $data['zhihu_q2']['intensity_max_2'] = sizeof($intensity_max_22) ? max($intensity_max_22) : 0; // 最大值
            $data['zhihu_q2']['intensity_min_2'] = sizeof($intensity_min_22) ? min($intensity_min_22) : 0; // 最小值
            $intensity_avg_22 = array_filter($intensity_avg_22);
            if(sizeof($intensity_avg_22) == 0){
                $data['zhihu_q2']['intensity_avg_2'] = 0; // 平均值
            }else{
                $data['zhihu_q2']['intensity_avg_2'] = round(array_sum($intensity_avg_22) / sizeof($intensity_avg_22),2); // 平均值
            }
            $mortar_standard_deviation_22 = array_filter($mortar_standard_deviation_22);
            if(sizeof($mortar_standard_deviation_22) == 0){
                $data['zhihu_q2']['mortar_standard_deviation_2'] = 0; // 标准差
            }else{
                $data['zhihu_q2']['mortar_standard_deviation_2'] = round(array_sum($mortar_standard_deviation_22) / sizeof($mortar_standard_deviation_22),2); // 标准差
            }
            $guarantee_rate_22 = array_filter($guarantee_rate_22);
            if(sizeof($guarantee_rate_22) == 0){
                $data['zhihu_q2']['mortar_standard_deviation_2'] = 0; // 保证率
            }else{
                $data['zhihu_q2']['mortar_standard_deviation_2'] = round(array_sum($guarantee_rate_22) / sizeof($guarantee_rate_22),2); // 保证率
            }
            $intensity_percent_22 = array_filter($intensity_percent_22);
            if(sizeof($intensity_percent_22) == 0){
                $data['zhihu_q2']['intensity_percent_2'] = 0; // 合格率Ps
            }else{
                $data['zhihu_q2']['intensity_percent_2'] = round(array_sum($intensity_percent_22) / sizeof($intensity_percent_22),2); // 合格率Ps
            }
        }

        /**
         * 支护统计 -- 总数据
         */
        // 喷砼厚度
        $data['zhihu_all']['supporting_area'] = array_sum($supporting_area_1); // 支护面积
        $data['zhihu_all']['thickness_number'] = array_sum($thickness_number_1); // 检测组数
        $data['zhihu_all']['max'] = sizeof($max_1) ? max($max_1) : 0; // 最大值
        $data['zhihu_all']['min'] = sizeof($min_1) ? min($min_1) : 0; // 最小值
        if($count_num_1 == 0){
            $data['zhihu_all']['avg_val'] = 0; // 平均值
            $data['zhihu_all']['pass'] = 0; // 合格率Ps
        }else{
            $data['zhihu_all']['avg_val'] = round(array_sum($avg_1) / $count_num_1,2); // 平均值
            $data['zhihu_all']['pass'] = round(array_sum($percent_1) / $count_num_1,2); // 合格率Ps
        }

        // 施工单位 --  喷砼强度
        $data['zhihu_all']['square_quantity'] = array_sum($square_quantity_1); // 方量
        $data['zhihu_all']['intensity_number'] = array_sum($intensity_number_1); // 检测组数
        $data['zhihu_all']['intensity_max'] = sizeof($intensity_max_1) ? max($intensity_max_1) : 0; // 最大值
        $data['zhihu_all']['intensity_min'] = sizeof($intensity_min_1) ? min($intensity_min_1) : 0; // 最小值
        $intensity_avg_1 = array_filter($intensity_avg_1);
        if(sizeof($intensity_avg_1) == 0){
            $data['zhihu_all']['intensity_avg'] = 0; // 平均值
        }else{
            $data['zhihu_all']['intensity_avg'] = round(array_sum($intensity_avg_1) / sizeof($intensity_avg_1),2); // 平均值
        }
        $intensity_percent_1 = array_filter($intensity_percent_1);
        if(sizeof($intensity_percent_1) == 0){
            $data['zhihu_all']['intensity_percent'] = 0; // 合格率Ps
        }else{
            $data['zhihu_all']['intensity_percent'] = round(array_sum($intensity_percent_1) / sizeof($intensity_percent_1),2); // 合格率Ps
        }
        // 监理单位 --  喷砼强度
        $data['zhihu_all']['square_quantity_2'] = array_sum($square_quantity_2); // 方量
        $data['zhihu_all']['intensity_number_2'] = array_sum($intensity_number_2); // 检测组数
        $data['zhihu_all']['intensity_max_2'] = sizeof($intensity_max_2) ? max($intensity_max_2) : 0; // 最大值
        $data['zhihu_all']['intensity_min_2'] = sizeof($intensity_min_2) ? min($intensity_min_2) : 0; // 最小值
        $intensity_avg_2 = array_filter($intensity_avg_2);
        if(sizeof($intensity_avg_2) == 0){
            $data['zhihu_all']['intensity_avg_2'] = 0; // 平均值
        }else{
            $data['zhihu_all']['intensity_avg_2'] = round(array_sum($intensity_avg_2) / sizeof($intensity_avg_2),2); // 平均值
        }
        $intensity_percent_2 = array_filter($intensity_percent_2);
        if(sizeof($intensity_percent_2) == 0){
            $data['zhihu_all']['intensity_percent_2'] = 0; // 合格率Ps
        }else{
            $data['zhihu_all']['intensity_percent_2'] = round(array_sum($intensity_percent_2) / sizeof($intensity_percent_2),2); // 合格率Ps
        }

        // 根据支护表获取关联的锚杆检测组
        $zhihu_maogan = Db::name('project_zhihu_maogan')->where(['uid'=>['in',$id_arr]])->select();
        if(sizeof($zhihu_maogan) < 1){
            $data['maogan'] = [];
        }else{
            // 锚杆统计 1 施工单位 2 监理单位
            $data['maogan'] = $this->getMaoGan($zhihu_maogan);
        }
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'支护统计数据'];
    }

    public function getMaoGan($zhihu_maogan)
    {
        $data['builder'] = $data['supervision_unit'] = [];
        $count_num_1 = 0; // 该统计项目下单元工程验收批数(即该项目下所有最小子项之和)
        // 锚杆砂浆强度==>检测组数,设计等级,最大值,最小值,平均值,标准差,保证率,合格率
        $mortar_number = $mortar_level = $mortar_max = $mortar_min = $mortar_avg = $mortar_standard_deviation = $guarantee_rate = $mortar_percent = [];
        $mortar_number2 = $mortar_level2 = $mortar_max2 = $mortar_min2 = $mortar_avg2 = $mortar_standard_deviation2 = $guarantee_rate2 = $mortar_percent2 = [];
        // 锚杆无损检测==>施工数量(个),抽检根数(个),合格根数（个）,锚杆型号,锚杆长度-最大,锚杆长度-最小，注浆密实度-最大， 注浆密实度-最小,合格率
        $nde_quantity = $nde_inspection_num = $nde_percent_num = $nde_model_number = $nde_max = $nde_min = $nde_density_max = $nde_density_min = $nde_percent= [];
        $nde_quantity2 = $nde_inspection_num2 = $nde_percent_num2 = $nde_model_number2 = $nde_max2 = $nde_min2 = $nde_density_max2 = $nde_density_min2 = $nde_percent2 = [];
        // 锚杆拉拔实验==>锚杆类型,设计拉拔力,检测根数（个）,合格率
        $anchor_type = $drawing_load = $experiment_inspection_num = $experiment_percent = [];
        $anchor_type2 = $drawing_load2 = $experiment_inspection_num2 = $experiment_percent2 = [];
        // unit_type 1 施工单位 2 监理单位
        foreach ($zhihu_maogan as $v){
            // ========= 施工单位 =======
            // 锚杆砂浆强度
            $mortar_number[] = $v['mortar_number'];
            $mortar_level[] = $v['mortar_level'];
            $mortar_max[] = $v['mortar_max'];
            $mortar_min[] = $v['mortar_min'];
            $mortar_avg[] = $v['mortar_avg'];
            // genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $data_2 = $this->getDesign(2,$v['id'],1,2);
            $mortar_standard_deviation[] = $data_2['design_val']; // 标准差
            $guarantee_rate[] = $data_2['guarantee_rate_1']; // 保证率
            $mortar_percent[] = $v['mortar_percent'];

            // 锚杆无损检测
            $nde_quantity[] = $v['nde_quantity'];
            $nde_inspection_num[] = $v['nde_inspection_num'];
            $nde_percent_num[] = $v['nde_percent_num'];
            $nde_model_number[] = $v['nde_model_number'];
            $nde_max[] = $v['nde_max'];
            $nde_min[] = $v['nde_min'];
            $nde_density_max[] = $v['nde_density_max'];
            $nde_density_min[] = $v['nde_density_min'];
            $nde_percent[] = $v['nde_percent'];

            // 锚杆拉拔实验
            $anchor_type[] = $v['anchor_type'];
            $drawing_load[] = $v['drawing_load'];
            $experiment_inspection_num[] = $v['experiment_inspection_num'];
            $experiment_percent[] = $v['experiment_percent'];


            // ========= 监理单位 =======
            // 锚杆砂浆强度
            $mortar_number2[] = $v['mortar_number_2'];
            $mortar_level2[] = $v['mortar_level_2'];
            $mortar_max2[] = $v['mortar_max_2'];
            $mortar_min2[] = $v['mortar_min_2'];
            $mortar_avg2[] = $v['mortar_avg_2'];
            // genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $data_2 = $this->getDesign(2,$v['id'],2,2);
            $mortar_standard_deviation2[] = $data_2['design_val']; // 标准差
            $guarantee_rate2[] = $data_2['guarantee_rate_1']; // 保证率
            $mortar_percent2[] = $v['mortar_percent_2'];

            // 锚杆无损检测
            $nde_quantity2[] = $v['nde_quantity_2'];
            $nde_inspection_num2[] = $v['nde_inspection_num_'];
            $nde_percent_num2[] = $v['nde_percent_num_2'];
            $nde_model_number2[] = $v['nde_model_number_2'];
            $nde_max2[] = $v['nde_max_2'];
            $nde_min2[] = $v['nde_min_2'];
            $nde_density_max2[] = $v['nde_density_max_2'];
            $nde_density_min2[] = $v['nde_density_min_2'];
            $nde_percent2[] = $v['nde_percent_2'];

            // 锚杆拉拔实验
            $anchor_type2[] = $v['anchor_type_2'];
            $drawing_load2[] = $v['drawing_load_2'];
            $experiment_inspection_num2[] = $v['experiment_inspection_num_2'];
            $experiment_percent2[] = $v['experiment_percent_2'];

            $count_num_1++;
        }

        // ========= 施工单位 分组 统计 =======

        /**
         * 锚杆砂浆强度
         * 按照 设计等级 分组 统计 ==》  设计等级相同的统计到一起 (求平均值)
         */
        $design_mortar_number = $design_mortar_max = $design_mortar_min = $design_mortar_avg = $design_mortar_standard_deviation = $design_guarantee_rate = $design_mortar_percent = [];
        $arr3 = array_count_values($mortar_level); // 每一个设计等级出现的次数
        $arr_3 = array_keys($arr3); // 相同的设计等级
        foreach ($mortar_level as $dk=>$dv) {
            $design_mortar_number[$dv][] = $mortar_number[$dk];
            $design_mortar_max[$dv][] = $mortar_max[$dk];
            $design_mortar_min[$dv][] = $mortar_min[$dk];
            $design_mortar_avg[$dv][] = $mortar_avg[$dk];
            $design_mortar_standard_deviation[$dv][] = $mortar_standard_deviation[$dk];
            $design_guarantee_rate[$dv][] = $guarantee_rate[$dk];
            $design_mortar_percent[$dv][] = $mortar_percent[$dk];
        }
        foreach($arr_3 as $arv) {
            $data['builder']['mortar_level'][] = $arv; // 设计等级
            $data['builder']['mortar_number'][] = array_sum($design_mortar_number[$arv]); // 检测组数
            $data['builder']['mortar_max'][] = sizeof($design_mortar_max[$arv]) ? max($design_mortar_max[$arv]) : 0; // 最大值
            $data['builder']['mortar_min'][] = sizeof($design_mortar_min[$arv]) ? min($design_mortar_min[$arv]) : 0; // 最小值
            if(sizeof($design_mortar_avg[$arv]) == 0){
                $data['builder']['mortar_avg'][] = 0; // 平均值
            }else{
                $data['builder']['mortar_avg'][] = round(array_sum($design_mortar_avg[$arv]) / sizeof($design_mortar_avg[$arv]), 2); // 平均值
            }
            if(sizeof($design_mortar_standard_deviation[$arv])){
                $data['builder']['mortar_standard_deviation_2'][] = round(array_sum($design_mortar_standard_deviation[$arv]) / sizeof($design_mortar_standard_deviation[$arv]),2); // 锚杆砂浆强度 -- 标准差
            }else{
                $design_mortar_standard_deviation[$arv] = 0;
            }
            if(sizeof($design_guarantee_rate[$arv])){
                $data['builder']['guarantee_rate_2'][] = round(array_sum($design_guarantee_rate[$arv]) / sizeof($design_guarantee_rate[$arv]),2); // 锚杆砂浆强度 -- 保证率
            }else{
                $design_guarantee_rate[$arv] = 0;
            }
            if(sizeof($design_mortar_percent[$arv]) == 0){
                $data['builder']['mortar_percent'][] = 0; // 合格率Ps
            }else{
                $data['builder']['mortar_percent'][] = round(array_sum($design_mortar_percent[$arv]) / sizeof($design_mortar_percent[$arv]), 2); // 合格率Ps
            }
        }

        /**
         * 锚杆无损检测
         * 按照 锚杆型号 分组 统计 ==》  锚杆型号相同的统计到一起 (求平均值)
         */
        $design_nde_quantity = $design_nde_inspection_num = $design_nde_percent_num = $design_nde_max = $design_nde_min = $design_nde_density_max = $design_nde_density_min = $design_nde_percent = [];
        $arr4 = array_count_values($nde_model_number); // 每一个锚杆型号出现的次数
        $arr_4 = array_keys($arr4); // 相同的锚杆型号
        foreach ($nde_model_number as $dk=>$dv) {
            $design_nde_quantity[$dv][] = $nde_quantity[$dk];
            $design_nde_inspection_num[$dv][] = $nde_inspection_num[$dk];
            $design_nde_percent_num[$dv][] = $nde_percent_num[$dk];
            $design_nde_max[$dv][] = $nde_max[$dk];
            $design_nde_min[$dv][] = $nde_min[$dk];
            $design_nde_density_max[$dv][] = $nde_density_max[$dk];
            $design_nde_density_min[$dv][] = $nde_density_min[$dk];
            $design_nde_percent[$dv][] = $nde_percent[$dk];
        }
        foreach($arr_4 as $arv) {
            $data['builder']['nde_model_number'] = $arv; // 锚杆型号
            $data['builder']['nde_quantity'][] = array_sum($design_nde_quantity[$arv]); // 施工数量(个)
            $data['builder']['nde_inspection_num'][] = array_sum($design_nde_inspection_num[$arv]); // 抽检根数(个)
            if(array_sum($design_nde_inspection_num[$arv]) == 0){
                $data['builder']['detection_ratio'][] = 0; // 检测比例
            }else{
                $data['builder']['detection_ratio'][] = round(array_sum($design_nde_quantity[$arv]) / array_sum($design_nde_inspection_num[$arv]), 2) * (100 / 100); // 检测比例
            }
            $data['builder']['nde_percent_num'][] = array_sum($design_nde_percent_num[$arv]); // 合格根数（个）
            $data['builder']['nde_max'][] = sizeof($design_nde_max[$arv]) ? max($design_nde_max[$arv]) : 0; // 锚杆长度-最大
            $data['builder']['nde_min'][] = sizeof($design_nde_min[$arv]) ? min($design_nde_min[$arv]) : 0; // 锚杆长度-最小
            $data['builder']['nde_density_max'][] = sizeof($design_nde_density_max[$arv]) ? max($design_nde_density_max[$arv]) : 0; // 注浆密实度-最大
            $data['builder']['nde_density_min'][] = sizeof($design_nde_density_min[$arv]) ? min($design_nde_density_min[$arv]) : 0; // 注浆密实度-最小
            if(sizeof($design_nde_percent[$arv]) == 0){
                $data['builder']['nde_percent'][] = 0; // 合格率Ps
            }else{
                $data['builder']['nde_percent'][] = round(array_sum($design_nde_percent[$arv]) / sizeof($design_nde_percent[$arv]), 2); // 合格率Ps
            }
        }

        /**
         * 锚杆拉拔实验
         * 按照 锚杆类型 分组 统计 ==》  锚杆类型相同的统计到一起 (求平均值)
         */
        $design_drawing_load = $design_experiment_inspection_num = $design_experiment_percent = [];
        $arr5 = array_count_values($anchor_type); // 每一个锚杆类型出现的次数
        $arr_5 = array_keys($arr5); // 相同的锚杆类型
        foreach ($anchor_type as $dk=>$dv){
            $design_drawing_load[$dv][] = $drawing_load[$dk];
            $design_experiment_inspection_num[$dv][] = $experiment_inspection_num[$dk];
            $design_experiment_percent[$dv][] = $experiment_percent[$dk];
        }
        foreach($arr_5 as $arv){
            $data['builder']['anchor_type'][] = $arv; // 锚杆类型
            $data['builder']['drawing_load'][] = $design_drawing_load[$arv]; // 设计拉拔力
            $data['builder']['experiment_inspection_num'][] = array_sum($design_experiment_inspection_num[$arv]); // 检测根数（个）
            if(sizeof($design_experiment_percent[$arv]) == 0){
                $data['builder']['experiment_percent'][] = 0; // 合格率Ps
            }else{
                $data['builder']['experiment_percent'][] = round(array_sum($design_experiment_percent[$arv]) / sizeof($design_experiment_percent[$arv]),2); // 合格率Ps
            }
        }

        // ========= 监理单位 =======
        /**
         * 锚杆砂浆强度
         * 按照 设计等级 分组 统计 ==》  设计等级相同的统计到一起 (求平均值)
         */
        $design_mortar_number2 = $design_mortar_max2 = $design_mortar_min2 = $design_mortar_avg2 = $design_mortar_standard_deviation2 = $design_guarantee_rate2 = $design_mortar_percent2 = [];
        $arr32 = array_count_values($mortar_level2); // 每一个设计等级出现的次数
        $arr_32 = array_keys($arr32); // 相同的设计等级
        foreach ($mortar_level2 as $dk=>$dv) {
            $design_mortar_number2[$dv][] = $mortar_number2[$dk];
            $design_mortar_max2[$dv][] = $mortar_max2[$dk];
            $design_mortar_min2[$dv][] = $mortar_min2[$dk];
            $design_mortar_avg2[$dv][] = $mortar_avg2[$dk];
            $design_mortar_standard_deviation2[$dv][] = $mortar_standard_deviation2[$dk];
            $design_guarantee_rate2[$dv][] = $guarantee_rate2[$dk];
            $design_mortar_percent2[$dv][] = $mortar_percent2[$dk];
        }
        foreach($arr_32 as $arv) {
            $data['supervision_unit']['mortar_level'][] = $arv; // 设计等级
            $data['supervision_unit']['mortar_number'][] = array_sum($design_mortar_number2[$arv]); // 检测组数
            $data['supervision_unit']['mortar_max'][] = sizeof($design_mortar_max2[$arv]) ? max($design_mortar_max2[$arv]) : 0; // 最大值
            $data['supervision_unit']['mortar_min'][] = sizeof($design_mortar_min2[$arv]) ? min($design_mortar_min2[$arv]) : 0; // 最小值
            if(sizeof($design_mortar_avg2[$arv]) == 0){
                $data['supervision_unit']['mortar_avg'][] = 0; // 平均值
            }else{
                $data['supervision_unit']['mortar_avg'][] = round(array_sum($design_mortar_avg2[$arv]) / sizeof($design_mortar_avg2[$arv]), 2); // 平均值
            }
            if(sizeof($design_mortar_standard_deviation2[$arv])){
                $data['supervision_unit']['mortar_standard_deviation_2'][] = round(array_sum($design_mortar_standard_deviation2[$arv]) / sizeof($design_mortar_standard_deviation2[$arv]),2); // 锚杆砂浆强度 -- 标准差
            }else{
                $design_mortar_standard_deviation2[$arv] = 0;
            }
            if(sizeof($design_guarantee_rate2[$arv])){
                $data['supervision_unit']['guarantee_rate_2'][] = round(array_sum($design_guarantee_rate2[$arv]) / sizeof($design_guarantee_rate2[$arv]),2); // 锚杆砂浆强度 -- 保证率
            }else{
                $design_guarantee_rate2[$arv] = 0;
            }
            if(sizeof($design_mortar_percent2[$arv]) == 0){
                $data['supervision_unit']['mortar_percent'][] = 0; // 合格率Ps
            }else{
                $data['supervision_unit']['mortar_percent'][] = round(array_sum($design_mortar_percent2[$arv]) / sizeof($design_mortar_percent2[$arv]), 2); // 合格率Ps
            }
        }

        /**
         * 锚杆无损检测
         * 按照 锚杆型号 分组 统计 ==》  锚杆型号相同的统计到一起 (求平均值)
         */
        $design_nde_quantity2 = $design_nde_inspection_num2 = $design_nde_percent_num2 = $design_nde_max2 = $design_nde_min2 = $design_nde_density_max2 = $design_nde_density_min2 = $design_nde_percent2 = [];
        $arr42 = array_count_values($nde_model_number2); // 每一个锚杆型号出现的次数
        $arr_42 = array_keys($arr42); // 相同的锚杆型号
        foreach ($nde_model_number2 as $dk=>$dv) {
            $design_nde_quantity2[$dv][] = $nde_quantity2[$dk];
            $design_nde_inspection_num2[$dv][] = $nde_inspection_num2[$dk];
            $design_nde_percent_num2[$dv][] = $nde_percent_num2[$dk];
            $design_nde_max2[$dv][] = $nde_max2[$dk];
            $design_nde_min2[$dv][] = $nde_min2[$dk];
            $design_nde_density_max2[$dv][] = $nde_density_max2[$dk];
            $design_nde_density_min2[$dv][] = $nde_density_min2[$dk];
            $design_nde_percent2[$dv][] = $nde_percent2[$dk];
        }
        foreach($arr_42 as $arv) {
            $data['supervision_unit']['nde_model_number'] = $arv; // 锚杆型号
            $data['supervision_unit']['nde_quantity'][] = array_sum($design_nde_quantity2[$arv]); // 施工数量(个)
            $data['supervision_unit']['nde_inspection_num'][] = array_sum($design_nde_inspection_num2[$arv]); // 抽检根数(个)
            if(array_sum($design_nde_inspection_num2[$arv]) == 0){
                $data['supervision_unit']['detection_ratio'][] = 0; // 检测比例
            }else{
                $data['supervision_unit']['detection_ratio'][] = round(array_sum($design_nde_quantity2[$arv]) / array_sum($design_nde_inspection_num2[$arv]), 2) * (100 / 100); // 检测比例
            }
            $data['supervision_unit']['nde_percent_num'][] = array_sum($design_nde_percent_num2[$arv]); // 合格根数（个）
            $data['supervision_unit']['nde_max'][] = sizeof($design_nde_max2[$arv]) ? max($design_nde_max2[$arv]) : 0; // 锚杆长度-最大
            $data['supervision_unit']['nde_min'][] = sizeof($design_nde_min2[$arv]) ? min($design_nde_min2[$arv]) : 0; // 锚杆长度-最小
            $data['supervision_unit']['nde_density_max'][] = sizeof($design_nde_density_max2[$arv]) ? max($design_nde_density_max2[$arv]) : 0; // 注浆密实度-最大
            $data['supervision_unit']['nde_density_min'][] = sizeof($design_nde_density_min2[$arv]) ? min($design_nde_density_min2[$arv]) : 0; // 注浆密实度-最小
            if(sizeof($design_nde_percent2[$arv]) == 0){
                $data['supervision_unit']['nde_percent'][] = 0; // 合格率Ps
            }else{
                $data['supervision_unit']['nde_percent'][] = round(array_sum($design_nde_percent2[$arv]) / sizeof($design_nde_percent2[$arv]), 2); // 合格率Ps
            }
        }

        /**
         * 锚杆拉拔实验
         * 按照 锚杆类型 分组 统计 ==》  锚杆类型相同的统计到一起 (求平均值)
         */
        $design_drawing_load2 = $design_experiment_inspection_num2 = $design_experiment_percent2 = [];
        $arr52 = array_count_values($anchor_type2); // 每一个锚杆类型出现的次数
        $arr_52 = array_keys($arr52); // 相同的锚杆类型
        foreach ($anchor_type2 as $dk=>$dv){
            $design_drawing_load2[$dv][] = $drawing_load2[$dk];
            $design_experiment_inspection_num2[$dv][] = $experiment_inspection_num2[$dk];
            $design_experiment_percent2[$dv][] = $experiment_percent2[$dk];
        }
        foreach($arr_52 as $arv){
            $data['supervision_unit']['anchor_type'][] = $arv; // 锚杆类型
            $data['supervision_unit']['drawing_load'][] = $design_drawing_load2[$arv]; // 设计拉拔力
            $data['supervision_unit']['experiment_inspection_num'][] = array_sum($design_experiment_inspection_num2[$arv]); // 检测根数（个）
            if(sizeof($design_experiment_percent2[$arv]) == 0){
                $data['supervision_unit']['experiment_percent'][] = 0; // 合格率Ps
            }else{
                $data['supervision_unit']['experiment_percent'][] = round(array_sum($design_experiment_percent2[$arv]) / sizeof($design_experiment_percent2[$arv]),2); // 合格率Ps
            }
        }


        // ========= 施工单位 =======
        /**
         * 统计总的数据
         */
        // 锚杆砂浆强度
        $data['builder_all']['mortar_number'] = array_sum($mortar_number); // 检测组数
        $data['builder_all']['mortar_max'] = sizeof($mortar_max) ? max($mortar_max) : 0; // 最大值
        $data['builder_all']['mortar_min'] = sizeof($mortar_min) ? max($mortar_min) : 0; // 最小值
        if(sizeof($mortar_avg) == 0){
            $data['builder_all']['mortar_avg'] = 0; // 平均值
        }else{
            $data['builder_all']['mortar_avg'] = round(array_sum($mortar_avg) / sizeof($mortar_avg),2); // 平均值
        }
        if(sizeof($mortar_percent) == 0){
            $data['builder_all']['mortar_percent'] = 0; // 合格率Ps
        }else{
            $data['builder_all']['mortar_percent'] = round(array_sum($mortar_percent) / sizeof($mortar_percent),2); // 合格率Ps
        }

        // 锚杆无损检测
        $data['builder_all']['nde_quantity'] = array_sum($nde_quantity); // 施工数量(个)
        $data['builder_all']['nde_inspection_num'] = array_sum($nde_inspection_num); // 抽检根数(个)
        if($data['builder_all']['nde_inspection_num'] == 0){
            $data['builder_all']['detection_ratio'] = 0; // 检测比例
        }else{
            $data['builder_all']['detection_ratio'] = round($data['builder_all']['nde_quantity'] / $data['builder_all']['nde_inspection_num'],2) * (100/100); // 检测比例
        }
        $data['builder_all']['nde_percent_num'] = array_sum($nde_percent_num); // 合格根数（个）
        $data['builder_all']['nde_max'] =  sizeof($nde_max) ? max($nde_max) : 0; // 锚杆长度-最大
        $data['builder_all']['nde_min'] = sizeof($nde_min) ? min($nde_min) : 0; // 锚杆长度-最小
        $data['builder_all']['nde_density_max'] = sizeof($nde_density_max) ? max($nde_density_max) : 0; // 注浆密实度-最大
        $data['builder_all']['nde_density_min'] = sizeof($nde_density_min) ? min($nde_density_min) : 0; // 注浆密实度-最小
        if(sizeof($nde_percent) == 0){
            $data['builder_all']['nde_percent'] = 0; // 合格率Ps
        }else{
            $data['builder_all']['nde_percent'] = round(array_sum($nde_percent) / sizeof($nde_percent),2); // 合格率Ps
        }

        // 锚杆拉拔实验
        $data['builder_all']['experiment_inspection_num'] = array_sum($experiment_inspection_num); // 检测根数（个）
        if(sizeof($experiment_percent) == 0){
            $data['builder_all']['experiment_percent'] = 0; // 合格率Ps
        }else{
            $data['builder_all']['experiment_percent'] = round(array_sum($experiment_percent) / sizeof($experiment_percent),2); // 合格率Ps
        }

        // ========= 监理单位 =======
        /**
         * 统计总的数据
         */
        // 锚杆砂浆强度
        $data['supervision_unit_all']['mortar_number'] = array_sum($mortar_number2); // 检测组数
        $data['supervision_unit_all']['mortar_max'] = sizeof($mortar_max2) ? max($mortar_max2) : 0; // 最大值
        $data['supervision_unit_all']['mortar_min'] = sizeof($mortar_min2) ? min($mortar_min2) : 0; // 最小值
        if(sizeof($mortar_avg2) == 0){
            $data['supervision_unit_all']['mortar_avg'] = 0; // 平均值
        }else{
            $data['supervision_unit_all']['mortar_avg'] = round(array_sum($mortar_avg2) / sizeof($mortar_avg2),2); // 平均值
        }
        if(sizeof($mortar_percent2) == 0){
            $data['supervision_unit_all']['mortar_percent'] = 0; // 合格率Ps
        }else{
            $data['supervision_unit_all']['mortar_percent'] = round(array_sum($mortar_percent2) / sizeof($mortar_percent2),2); // 合格率Ps
        }

        // 锚杆无损检测
        $data['supervision_unit_all']['nde_quantity'] = array_sum($nde_quantity2); // 施工数量(个)
        $data['supervision_unit_all']['nde_inspection_num'] = array_sum($nde_inspection_num2); // 抽检根数(个)
        if($data['supervision_unit_all']['nde_inspection_num'] == 0){
            $data['supervision_unit_all']['detection_ratio'] = 0; // 检测比例
        }else{
            $data['supervision_unit_all']['detection_ratio'] = round($data['supervision_unit_all']['nde_quantity'] / $data['supervision_unit_all']['nde_inspection_num'],2) * (100/100); // 检测比例
        }
        $data['supervision_unit_all']['nde_percent_num'] = array_sum($nde_percent_num2); // 合格根数（个）
        $data['supervision_unit_all']['nde_max'] = sizeof($nde_max2) ? max($nde_max2) : 0; // 锚杆长度-最大
        $data['supervision_unit_all']['nde_min'] = sizeof($nde_min2) ? min($nde_min2) : 0; // 锚杆长度-最小
        $data['supervision_unit_all']['nde_density_max'] = sizeof($nde_density_max2) ?  max($nde_density_max2) : 0; // 注浆密实度-最大
        $data['supervision_unit_all']['nde_density_min'] = sizeof($nde_density_min2) ? min($nde_density_min2) : 0; // 注浆密实度-最小
        if(sizeof($nde_percent2) == 0){
            $data['supervision_unit_all']['nde_percent'] = 0; // 合格率Ps
        }else{
            $data['supervision_unit_all']['nde_percent'] = round(array_sum($nde_percent2) / sizeof($nde_percent2),2); // 合格率Ps
        }

        // 锚杆拉拔实验
        $data['supervision_unit_all']['experiment_inspection_num'] = array_sum($experiment_inspection_num2); // 检测根数（个）
        if(sizeof($experiment_percent2) == 0){
            $data['supervision_unit_all']['experiment_percent'] = 0; // 合格率Ps
        }else{
            $data['supervision_unit_all']['experiment_percent'] = round(array_sum($experiment_percent2) / sizeof($experiment_percent2),2); // 合格率Ps
        }

        return $data;
    }

    // 计算标准差 和 百分率 保证率
    public function getDesign($genre,$id,$unit_type,$type)
    {
        /**
         * 设计强度标准值 是检测数据是否合格的参照值 施工单位和监理单位的值都一样
         * 施工单位 -- 喷砼强度
         * 比如 施工单位的检测组数 是 3 那么
         *  设计强度标准值    试验强度值
         *      5                   2
         *      5                   3
         *      5                   4
         * 监理单位 -- 喷砼强度
         * 比如 监理单位的检测组数 是 2 那么
         * 设计强度标准值    试验强度值
         *      5               4
         *      5               3
         */
        $design_val = $f = $m = $n = $guarantee_rate_1 = 0;
        // genre 1支护2锚杆3混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
        // 获取 设计强度标准值
        $standard_value = Db::name('project_standard_deviation')->where(['genre'=>$genre,'gid'=>['eq',$id],'unit_type'=>$unit_type,'type'=>$type])->value('standard_value');
        // 获取所有的试验强度值
        $design = Db::name('project_standard_deviation')->where(['genre'=>$genre,'gid'=>['eq',$id],'unit_type'=>$unit_type,'type'=>$type])->column('intensity_value');
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
                if($d >= $standard_value){
                    $no = $no + 1;
                }
            }
            if($n <= 1){
                $design_val = sqrt( ( pow($f,2) - $n * pow($m,2) ) / 1 ); // 套用公式计算
                $percentage = ($no / 1) * (100/100); // 百分率
            }else{
                $design_val = sqrt( ( pow($f,2) - $n * pow($m,2) ) / ($n-1) ); // 套用公式计算
                $percentage = ($no / $n) * (100/100); // 百分率
            }
        }

        $guarantee_rate_arr = [65.5,69.2,72.5,75.8,78.8,80.0,82.9,85.0,90.0,93.3,95.0,97.7,99.9];
        $t_arr = [0.40,0.50,0.60,0.70,0.80,0.84,0.95,1.04,1.28,1.50,1.65,2.0,3.0];
        if($design_val == 0){
            $t = round(($m - $f) / 1,1); // 概率度系数
        }else{
            $t = round(($m - $f) / $design_val,1); // 概率度系数
        }
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
        $unit_id = $this->projectIdArr($id,[$cate]);
        // 根据 单元工程检验批 获取 所有的混凝土 信息
        $h_data = Db::name('project_hunningtu')->where(['uid'=>['in',$unit_id]])->select();
        if(sizeof($h_data) < 1){
            return ['code'=>1,'excavate_data'=>[],'msg'=>'混凝土统计数据 -- 数据为空'];
        }
        $data = $this->getConcrete($h_data);
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'混凝土统计数据'];
    }

    public function getConcrete($h_data)
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
        $resist_design_index2 = $resist_age2 = $resist_test_group2 = $resist_max2 = $resist_min2 = $resist_avg2 = $mortar_standard_deviation_3 = $guarantee_rate_3 = $mortar_standard_deviation_4 = $guarantee_rate_4 = [];
        // (全面性能)==》设计指标 == 龄期 == (抗冻)取样组数 == (抗冻)测值 == (抗冻)合格率 == (抗渗)取样组数 == (抗渗)测值 == (抗渗)合格率
        $etc_design_index = $etc_age = $etc_anti_groups = $etc_anti_test = $etc_anti_pass = $etc_impervious_groups = $etc_impervious_test = $etc_impervious_pass = [];
        $etc_design_index2 = $etc_age2 = $etc_anti_groups2 = $etc_anti_test2 = $etc_anti_pass2 = $etc_impervious_groups2 = $etc_impervious_test2 = $etc_impervious_pass2 = [];
        // (形体偏差)==》(平面)测点数(个) == (平面)偏差范围 == (平面)合格率 == (竖面)测点数(个) == (竖面)偏差范围 == (竖面)合格率
        $deviation_plane_num = $deviation_plane_scope = $deviation_plane_pass = $deviation_vertical_num = $deviation_vertical_scope = $deviation_vertical_pass = [];
        // 将数据库里的值 都取出来 放到数组里, 用于统计
        foreach ($h_data as $v) {
            // (出口机)
            $ex_control_criterion[] = $v['ex_control_criterion'];
            $ex_test_groups[] = $v['ex_test_groups'];
            $ex_qualified_groups[] = $v['ex_qualified_groups'];
            $ex_max[] = $v['ex_max'];
            $ex_min[] = $v['ex_min'];
            $ex_avg[] = $v['ex_avg'];
            $ex_pass[] = $v['ex_pass'];
            // (入仓)
            $be_measurement[] = $v['be_measurement'];
            $be_max[] = $v['be_max'];
            $be_min[] = $v['be_min'];
            $be_avg[] = $v['be_avg'];
            $be_pass[] = $v['be_pass'];
            $be_num[] = $v['be_num'];
            // (浇筑)
            $pouring_measurement[] = $v['pouring_measurement'];
            $pouring_max[] = $v['pouring_max'];
            $pouring_min[] = $v['pouring_min'];
            $pouring_avg[] = $v['pouring_avg'];
            $pouring_pass[] = $v['pouring_pass'];
            $pouring_num[] = $v['pouring_num'];
            // (拌和物)
            $mix_design[] = $v['mix_design'];
            $mix_num[] = $v['mix_num'];
            $mix_qualified_num[] = $v['mix_qualified_num'];
            $mix_max[] = $v['mix_max'];
            $mix_min[] = $v['mix_min'];
            $mix_avg[] = $v['mix_avg'];
            $mix_pass[] = $v['mix_pass'];
            // 施工单位 -- (抗压强度)
            $resist_design_index[] = $v['resist_design_index'];
            $resist_age[] = $v['resist_age'];
            $resist_test_group[] = $v['resist_test_group'];
            $resist_max[] = $v['resist_max'];
            $resist_min[] = $v['resist_min'];
            $resist_avg[] = $v['resist_avg'];
            // genre 1 支护 2混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $data_1 = $this->getDesign(2,$v['id'],1,1);
            $data_2 = $this->getDesign(2,$v['id'],1,2);
            $mortar_standard_deviation_1[] = $data_1['design_val']; //  1喷砼强度    标准差
            $mortar_standard_deviation_2[] = $data_2['design_val']; // 2锚杆砂浆强度 标准差
            $guarantee_rate_1[] = $data_1['guarantee_rate_1']; // 1喷砼强度     保证率
            $guarantee_rate_2[] = $data_2['guarantee_rate_1']; // 2锚杆砂浆强度 保证率
            // 监理单位 -- (抗压强度)
            $resist_design_index2[] = $v['resist_design_index_2'];
            $resist_age2[] = $v['resist_age_2'];
            $resist_test_group2[] = $v['resist_test_group_2'];
            $resist_max2[] = $v['resist_max_2'];
            $resist_min2[] = $v['resist_min_2'];
            $resist_avg2[] = $v['resist_avg_2'];
            // genre 1 支护 2混凝土 gid 支护,锚杆,混凝土主键  unit_type 1施工单位2监理单位 type 1喷砼强度 2锚杆砂浆强度
            $data_3 = $this->getDesign(2,$v['id'],2,1);
            $data_4 = $this->getDesign(2,$v['id'],2,2);
            $mortar_standard_deviation_3[] = $data_3['design_val']; //  1喷砼强度    标准差
            $mortar_standard_deviation_4[] = $data_4['design_val']; // 2锚杆砂浆强度 标准差
            $guarantee_rate_3[] = $data_3['guarantee_rate_1']; // 1喷砼强度     保证率
            $guarantee_rate_4[] = $data_4['guarantee_rate_1']; // 2锚杆砂浆强度 保证率
            // 施工单位 -- (全面性能)
            $etc_design_index[] = $v['etc_design_index'];
            $etc_age[] = $v['etc_age'];
            $etc_anti_groups[] = $v['etc_anti_groups'];
            $etc_anti_test[] = $v['etc_anti_test'];
            $etc_anti_pass[] = $v['etc_anti_pass'];
            $etc_impervious_groups[] = $v['etc_impervious_groups'];
            $etc_impervious_test[] = $v['etc_impervious_test'];
            $etc_impervious_pass[] = $v['etc_impervious_pass'];
            // 施工单位 -- (全面性能)
            $etc_design_index2[] = $v['etc_design_index_2'];
            $etc_age2[] = $v['etc_age_2'];
            $etc_anti_groups2[] = $v['etc_anti_groups_2'];
            $etc_anti_test2[] = $v['etc_anti_test_2'];
            $etc_anti_pass2[] = $v['etc_anti_pass_2'];
            $etc_impervious_groups2[] = $v['etc_impervious_groups_2'];
            $etc_impervious_test2[] = $v['etc_impervious_test_2'];
            $etc_impervious_pass2[] = $v['etc_impervious_pass_2'];
            // (形体偏差)
            $deviation_plane_num[] = $v['deviation_plane_num'];
            $deviation_plane_scope[] = $v['deviation_plane_scope'];
            $deviation_plane_pass[] = $v['deviation_plane_pass'];
            $deviation_vertical_num[] = $v['deviation_vertical_num'];
            $deviation_vertical_scope[] = $v['deviation_vertical_scope'];
            $deviation_vertical_pass[] = $v['deviation_vertical_pass'];
        }

        // 按照 控制标准 分组 统计 ==》 控制标准 相同的统计到一起 (求平均值)
        $control_ex_test_groups = $control_ex_qualified_groups = $control_ex_max = $control_ex_min = $control_ex_avg = $control_ex_pass = [];
        $arr = array_count_values($ex_control_criterion); // 每一个 控制标准 出现的次数
        $arr_1 = array_keys($arr); // 相同的 控制标准
        foreach ($ex_control_criterion as $dk=>$dv){
            $control_ex_test_groups[$dv][] = $ex_test_groups[$dk];
            $control_ex_qualified_groups[$dv][] = $ex_qualified_groups[$dk];
            $control_ex_max[$dv][] = $ex_max[$dk];
            $control_ex_min[$dv][] = $ex_min[$dk];
            $control_ex_avg[$dv][] = $ex_avg[$dk];
            $control_ex_pass[$dv][] = $ex_pass[$dk];
        }
        /**
         * (出口机)
         */
        $data['ex'] = [];
        foreach($arr_1 as $arv){
            $data['ex']['ex_control_criterion'][] = $arv; // 相同的设计值
            $data['ex']['ex_test_groups'][] = array_sum($control_ex_test_groups[$arv]); // 检测组数（个）
            $data['ex']['ex_qualified_groups'][] = array_sum($control_ex_qualified_groups[$arv]); // 合格组数（个）
            $data['ex']['ex_max'][] = max($control_ex_max[$arv]); // 最大值
            $data['ex']['ex_min'][] = min($control_ex_min[$arv]); // 最小值
            if(sizeof($control_ex_avg[$arv]) == 0){
                $data['ex']['ex_avg'][] = 0; // 平均值
            }else{
                $data['ex']['ex_avg'][] = round(array_sum($control_ex_avg[$arv]) / sizeof($control_ex_avg[$arv]),2); // 平均值
            }
            if(sizeof($control_ex_pass[$arv]) == 0){
                $data['ex']['ex_pass'][] = 0; // 合格率Ps
            }else{
                $data['ex']['ex_pass'][] = round(array_sum($control_ex_pass[$arv]) / sizeof($control_ex_pass[$arv]),2); // 合格率Ps
            }
        }

        // 按照 设计指标 分组 统计 ==》 设计指标 相同的统计到一起 (求平均值)
        $mix_num11 = $mix_qualified_num11 = $mix_max11 = $mix_min11 = $mix_avg11 = $mix_pass11 = [];
        $mix_arr = array_count_values($mix_design); // 每一个 设计指标 出现的次数
        $mix_arr_1 = array_keys($mix_arr); // 相同的 设计指标
        foreach ($mix_design as $dk=>$dv) {
            $mix_num11[$dv][] = $mix_num[$dk];
            $mix_qualified_num11[$dv][] = $mix_qualified_num[$dk];
            $mix_max11[$dv][] = $mix_max[$dk];
            $mix_min11[$dv][] = $mix_min[$dk];
            $mix_avg11[$dv][] = $mix_avg[$dk];
            $mix_pass11[$dv][] = $mix_pass[$dk];
        }
        /**
         * (拌和物)
         */
        $data['mix'] = [];
        foreach($mix_arr_1 as $arv) {
            $data['mix']['mix_design'][] = $arv; // 设计指标
            $data['mix']['mix_num'][] = array_sum($mix_num11[$arv]); // 检测次数
            $data['mix']['mix_qualified_num'][] = array_sum($mix_qualified_num11[$arv]); // 合格次数
            $data['mix']['mix_max'][] = sizeof($mix_max11[$arv]) ? max($mix_max11[$arv]) : 0; // 最大值
            $data['mix']['mix_min'][] = sizeof($mix_min11[$arv]) ? min($mix_min11[$arv]) : 0; // 最小值
            if(sizeof($mix_avg11[$arv]) == 0){
                $data['mix']['mix_avg'][] = 0; // 平均值
            }else{
                $data['mix']['mix_avg'][] = round(array_sum($mix_avg11[$arv]) / sizeof($mix_avg11[$arv]), 2); // 平均值
            }
            if(sizeof($mix_pass11[$arv]) == 0){
                $data['mix']['mix_pass'][] = 0; // 合格率Ps
            }else{
                $data['mix']['mix_pass'][] = round(array_sum($mix_pass11[$arv]) / sizeof($mix_pass11[$arv]), 2); // 合格率Ps
            }
        }

        // 按照 设计指标 分组 统计 ==》 设计指标 相同的统计到一起 (求平均值)
        $resist_age11 = $resist_test_group11 = $resist_max11 = $resist_min11 = $resist_avg11 = $mortar_standard_deviation_111 = $guarantee_rate_111 = $mortar_standard_deviation_211 = $guarantee_rate_211 = [];
        $res_arr = array_count_values($resist_design_index); // 每一个 设计指标 出现的次数
        $res_arr_1 = array_keys($res_arr); // 相同的 设计指标
        foreach ($resist_design_index as $dk=>$dv) {
            $resist_age11[$dv][] = $resist_age[$dk];
            $resist_test_group11[$dv][] = $resist_test_group[$dk];
            $resist_max11[$dv][] = $resist_max[$dk];
            $resist_min11[$dv][] = $resist_min[$dk];
            $resist_avg11[$dv][] = $resist_avg[$dk];
            $mortar_standard_deviation_111[$dv][] = $mortar_standard_deviation_1[$dk];
            $mortar_standard_deviation_211[$dv][] = $mortar_standard_deviation_2[$dk];
            $guarantee_rate_111[$dv][] = $guarantee_rate_1[$dk];
            $guarantee_rate_211[$dv][] = $guarantee_rate_2[$dk];
        }

        /**
         * 施工单位 (抗压强度)
         */
        $data['builder'] = [];
        foreach($res_arr_1 as $arv) {
            $data['builder']['resist_design_index'][] = $arv; // 设计指标
            $data['builder']['resist_age'][] = $resist_age11[$arv]; // 龄期
            $data['builder']['resist_test_group'][] = $resist_test_group11[$arv]; // 检查组数
            $data['builder']['resist_max'][] = sizeof($resist_max11) ? max($resist_max11[$arv]) : 0; // 最大值
            $data['builder']['resist_min'][] = sizeof($resist_min11[$arv]) ? min($resist_min11[$arv]) : 0; // 最小值
            if(sizeof($resist_avg11[$arv]) == 0){
                $data['builder']['resist_avg'][] = 0; // 平均值
            }else{
                $data['builder']['resist_avg'][] = round(array_sum($resist_avg11[$arv]) / sizeof($resist_avg11[$arv]), 2); // 平均值
            }
            $data['builder']['mortar_standard_deviation_1'][] = $mortar_standard_deviation_111[$arv]; // 喷砼强度 -- 标准差
            $data['builder']['guarantee_rate_1'][] = $guarantee_rate_111[$arv]; // 喷砼强度 -- 保证率
            $data['builder']['mortar_standard_deviation_2'][] = $mortar_standard_deviation_211[$arv]; // 锚杆砂浆强度 -- 标准差
            $data['builder']['guarantee_rate_2'][] = $guarantee_rate_211[$arv]; // 锚杆砂浆强度 -- 保证率
        }

        // 按照 设计指标 分组 统计 ==》 设计指标 相同的统计到一起 (求平均值)
        $resist_age22 = $resist_test_group22 = $resist_max22 = $resist_min22 = $resist_avg22 = $mortar_standard_deviation_122 = $guarantee_rate_122 = $mortar_standard_deviation_222 = $guarantee_rate_222 = [];
        $res_arr2 = array_count_values($resist_design_index2); // 每一个 设计指标 出现的次数
        $res_arr_2 = array_keys($res_arr2); // 相同的 设计指标
        foreach ($resist_design_index2 as $dk=>$dv) {
            $resist_age22[$dv][] = $resist_age2[$dk];
            $resist_test_group22[$dv][] = $resist_test_group2[$dk];
            $resist_max22[$dv][] = $resist_max2[$dk];
            $resist_min22[$dv][] = $resist_min2[$dk];
            $resist_avg22[$dv][] = $resist_avg2[$dk];
            $mortar_standard_deviation_222[$dv][] = $mortar_standard_deviation_2[$dk];
            $guarantee_rate_222[$dv][] = $guarantee_rate_2[$dk];
        }

        /**
         * 监理单位 (抗压强度)
         */
        $data['supervision_unit'] = [];
        foreach($res_arr_1 as $arv) {
            $data['supervision_unit']['resist_design_index'][] = $arv; // 设计指标
            $data['supervision_unit']['resist_age'][] = $resist_age11[$arv]; // 龄期
            $data['supervision_unit']['resist_test_group'][] = $resist_test_group11[$arv]; // 检查组数
            $data['supervision_unit']['resist_max'][] = sizeof($resist_max11[$arv]) ? max($resist_max11[$arv]) : 0; // 最大值
            $data['supervision_unit']['resist_min'][] = sizeof($resist_min11[$arv]) ? min($resist_min11[$arv]) : 0; // 最小值
            if(sizeof($resist_avg11[$arv]) == 0){
                $data['supervision_unit']['resist_avg'][] = 0; // 平均值
            }else{
                $data['supervision_unit']['resist_avg'][] = round(array_sum($resist_avg11[$arv]) / sizeof($resist_avg11[$arv]), 2); // 平均值
            }
            $data['supervision_unit']['mortar_standard_deviation_1'][] = $mortar_standard_deviation_111[$arv]; // 喷砼强度 -- 标准差
            $data['supervision_unit']['guarantee_rate_1'][] = $guarantee_rate_111[$arv]; // 喷砼强度 -- 保证率
            $data['supervision_unit']['mortar_standard_deviation_2'][] = $mortar_standard_deviation_211[$arv]; // 锚杆砂浆强度 -- 标准差
            $data['supervision_unit']['guarantee_rate_2'][] = $guarantee_rate_211[$arv]; // 锚杆砂浆强度 -- 保证率
        }

        // 按照 设计指标 分组 统计 ==》 设计指标 相同的统计到一起 (求平均值)
        $etc_age11 = $etc_anti_groups11 = $etc_anti_test11 = $etc_anti_pass11 = $etc_impervious_groups11 = $etc_impervious_test11 = $etc_impervious_pass11 = [];
        $etc_arr = array_count_values($etc_design_index); // 每一个 设计指标 出现的次数
        $etc_arr_1 = array_keys($etc_arr); // 相同的 设计指标
        foreach ($etc_design_index as $dk=>$dv) {
            $etc_age11[$dv][] = $etc_age[$dk];
            $etc_anti_groups11[$dv][] = $etc_anti_groups[$dk];
            $etc_anti_test11[$dv][] = $etc_anti_test[$dk];
            $etc_anti_pass11[$dv][] = $etc_anti_pass[$dk];
            $etc_impervious_groups11[$dv][] = $etc_impervious_groups[$dk];
            $etc_impervious_test11[$dv][] = $etc_impervious_test[$dk];
            $etc_impervious_pass11[$dv][] = $etc_impervious_pass[$dk];
        }
        /**
         * (全面性能)
         */
        $data['etc'] = [];
        foreach($etc_arr_1 as $arv) {
            // (全面性能)
            $data['etc']['etc_design_index'][] = $arv; // 设计指标
            $data['etc']['etc_age'][] = $etc_age11[$arv]; // 龄期
            $data['etc']['etc_anti_groups'][] = array_sum($etc_anti_groups11[$arv]); // (抗冻)取样组数
            $data['etc']['etc_anti_test'][] = $etc_anti_test11[$arv]; // (抗冻)测值
            if(sizeof($etc_anti_pass11[$arv]) == 0){
                $data['etc']['etc_anti_pass'][] = 0; // (抗冻)合格率
            }else{
                $data['etc']['etc_anti_pass'][] = round(array_sum($etc_anti_pass11[$arv]) / sizeof($etc_anti_pass11[$arv]), 2); // (抗冻)合格率
            }
            $data['etc']['etc_impervious_groups'][] = array_sum($etc_impervious_groups11[$arv]); // (抗渗)取样组数
            $data['etc']['etc_impervious_test'][] = $etc_impervious_test11[$arv]; // (抗渗)测值
            if(sizeof($etc_impervious_pass11[$arv]) == 0){
                $data['etc']['etc_impervious_pass'][] = 0; // (抗渗)合格率
            }else{
                $data['etc']['etc_impervious_pass'][] = round(array_sum($etc_impervious_pass11[$arv]) / sizeof($etc_impervious_pass11[$arv]), 2); // (抗渗)合格率
            }
        }

        // (出口机)
        $data['all']['ex_test_groups'] = array_sum($ex_test_groups); // 检测组数（个）
        $data['all']['ex_qualified_groups'] = array_sum($ex_qualified_groups); // 合格组数（个）
        $data['all']['ex_max'] = sizeof($ex_max) ? max($ex_max) : 0; // 最大值
        $data['all']['ex_min'] = sizeof($ex_min) ? min($ex_min) : 0; // 最小值
        if(sizeof($ex_avg) == 0){
            $data['all']['ex_avg'] = 0; // 平均值
        }else{
            $data['all']['ex_avg'] = round(array_sum($ex_avg) / sizeof($ex_avg),2); // 平均值
        }
        if(sizeof($ex_pass) == 0){
            $data['all']['ex_pass'] = 0; // 合格率Ps
        }else{
            $data['all']['ex_pass'] = round(array_sum($ex_pass) / sizeof($ex_pass),2); // 合格率Ps
        }
        // (入仓)
        $data['all']['be_measurement'] = array_sum($be_measurement); // 测次
        $data['all']['be_max'] = sizeof($be_max) ? max($be_max) : 0; // 最大值
        $data['all']['be_min'] = sizeof($be_min) ? min($be_min) : 0; // 最小值
        if(sizeof($be_avg) == 0){
            $data['all']['be_avg'] = 0; // 平均值
        }else{
            $data['all']['be_avg'] = round(array_sum($be_avg) / sizeof($be_avg),2); // 平均值
        }
        if(sizeof($be_pass) == 0){
            $data['all']['be_pass'] = 0; // 合格率Ps
        }else{
            $data['all']['be_pass'] = round(array_sum($be_pass) / sizeof($be_pass),2); // 合格率Ps
        }
        $data['all']['be_num'] = array_sum($be_num); // 合格次数（个）

        // (浇筑)
        $data['all']['pouring_measurement'] = array_sum($pouring_measurement); // 测次
        $data['all']['pouring_max'] = sizeof($pouring_max) ? max($pouring_max) : 0; // 最大值
        $data['all']['pouring_min'] = sizeof($pouring_min) ? min($pouring_min) : 0; // 最小值
        if(sizeof($pouring_avg) == 0){
            $data['all']['pouring_avg'] = 0; // 平均值
        }else{
            $data['all']['pouring_avg'] = round(array_sum($pouring_avg) / sizeof($pouring_avg),2); // 平均值
        }
        if(sizeof($pouring_pass) == 0){
            $data['all']['pouring_pass'] = 0; // 合格率Ps
        }else{
            $data['all']['pouring_pass'] = round(array_sum($pouring_pass) / sizeof($pouring_pass),2); // 合格率Ps
        }
        $data['all']['pouring_num'] = array_sum($pouring_num); // 合格次数（个）

        // (拌和物)
        $data['all']['mix_num'] = array_sum($mix_num); // 检测次数
        $data['all']['mix_qualified_num'] = array_sum($mix_qualified_num); // 合格次数
        $data['all']['mix_max'] = sizeof($mix_max) ? max($mix_max) : 0; // 最大值
        $data['all']['mix_min'] = sizeof($mix_min) ? min($mix_min) : 0; // 最小值
        if(sizeof($mix_avg) == 0){
            $data['all']['mix_avg'] = 0; // 平均值
        }else{
            $data['all']['mix_avg'] = round(array_sum($mix_avg) / sizeof($mix_avg),2); // 平均值
        }
        if(sizeof($mix_pass) == 0){
            $data['all']['mix_pass'] = 0; // 合格率Ps
        }else{
            $data['all']['mix_pass'] = round(array_sum($mix_pass) / sizeof($mix_pass),2); // 合格率Ps
        }

        // (抗压强度)
        $data['all']['resist_test_group'] = array_sum($resist_test_group); // 检查组数
        $data['all']['resist_max'] = sizeof($resist_max) ? max($resist_max) : 0; // 最大值
        $data['all']['resist_min'] = sizeof($resist_min) ? min($resist_min) : 0; // 最小值
        if(sizeof($resist_avg) == 0){
            $data['all']['resist_avg'] = 0; // 平均值
        }else{
            $data['all']['resist_avg'] = round(array_sum($resist_avg) / sizeof($resist_avg),2); // 平均值
        }

        // (全面性能)

        $data['all']['etc_anti_groups'] = array_sum($etc_anti_groups); // (抗冻)取样组数
        if(sizeof($etc_anti_pass) == 0){
            $data['all']['etc_anti_pass'] = 0; // (抗冻)合格率
        }else{
            $data['all']['etc_anti_pass'] = round(array_sum($etc_anti_pass) / sizeof($etc_anti_pass),2); // (抗冻)合格率
        }
        $data['all']['etc_impervious_groups'] = array_sum($etc_impervious_groups); // (抗渗)取样组数
        $data['all']['etc_impervious_test'] = $etc_impervious_test; // (抗渗)测值
        if(sizeof($etc_impervious_pass) == 0){
            $data['all']['etc_impervious_pass'] = 0; // (抗渗)合格率
        }else{
            $data['all']['etc_impervious_pass'] = round(array_sum($etc_impervious_pass) / sizeof($etc_impervious_pass),2); // (抗渗)合格率
        }

        // (形体偏差)
        $data['all']['deviation_plane_num'] = array_sum($deviation_plane_num); // (平面)测点数(个)
        $data['all']['deviation_plane_scope'] = [min($deviation_plane_scope),max($deviation_plane_scope)]; // (平面)偏差范围
        if(sizeof($deviation_plane_pass) == 0){
            $data['all']['deviation_plane_pass'] = 0; // (平面)合格率
        }else{
            $data['all']['deviation_plane_pass'] = round(array_sum($deviation_plane_pass) / sizeof($deviation_plane_pass),2); // (平面)合格率
        }
        $data['all']['deviation_plane_num'] = array_sum($deviation_vertical_num); // (竖面)测点数(个)
        $data['all']['deviation_vertical_scope'] = [min($deviation_vertical_scope),max($deviation_vertical_scope)]; // (竖面)偏差范围
        if(sizeof($deviation_vertical_pass) == 0){
            $data['all']['deviation_vertical_pass'] = 0; // (竖面)合格率
        }else{
            $data['all']['deviation_vertical_pass'] = round(array_sum($deviation_vertical_pass) / sizeof($deviation_vertical_pass),2); // (竖面)合格率
        }

        return $data;
    }

    /**
     * 排水孔
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
        $unit_id = $this->projectIdArr($id,[$cate]);
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

        /**
         * 按不同设计孔深分开显示  分组 统计 ==》 设计孔深 相同的统计到一起 (求平均值)
         */
        $sampling_quantity2 = $hole_depth_avg2 = $hole_depth_percent2 = $hole_site_avg2 = $hole_site_percent2 = $aperture_avg2 = $aperture_percent2 = $pore_slant_avg2 = $pore_slant_percent2 = [];
        $arr = array_count_values($design_hole_depth); // 每一个 设计孔深 出现的次数
        $arr_1 = array_keys($arr); // 相同的 设计孔深
        foreach ($design_hole_depth as $dk=>$dv) {
            $sampling_quantity2[$dv][] = $sampling_quantity[$dk];
            $hole_depth_avg2[$dv][] = $hole_depth_avg[$dk];
            $hole_depth_percent2[$dv][] = $hole_depth_percent[$dk];
            $hole_site_avg2[$dv][] = $hole_site_avg[$dk];
            $hole_site_percent2[$dv][] = $hole_site_percent[$dk];
            $aperture_avg2[$dv][] = $aperture_avg[$dk];
            $aperture_percent2[$dv][] = $aperture_percent[$dk];
            $pore_slant_avg2[$dv][] = $pore_slant_avg[$dk];
            $pore_slant_percent2[$dv][] = $pore_slant_percent[$dk];
        }
        $data['design'] = [];
        foreach($arr_1 as $arv) {
            $data['design']['design_hole_depth'][] = $arv; // 设计孔深
            $data['design']['sampling_quantity'] = array_sum($sampling_quantity2); // 抽检数量
            if(sizeof(array_sum($hole_depth_avg2))){
                $data['design']['hole_depth_avg'] = round(array_sum($hole_depth_avg2) / sizeof(array_sum($hole_depth_avg2)),2); // 孔深平均值
            }else{
                $data['design']['hole_depth_avg'] = 0; // 孔深平均值
            }
            if(sizeof(array_sum($hole_depth_percent2))){
                $data['design']['hole_depth_percent'] = round(array_sum($hole_depth_percent2) / sizeof(array_sum($hole_depth_percent2)),2); // 孔深合格率
            }else{
                $data['design']['hole_depth_percent'] = 0; // 孔深合格率
            }
            if(sizeof(array_sum($hole_depth_avg2))){
                $data['design']['hole_site_avg'] = round(array_sum($hole_site_avg2) / sizeof(array_sum($hole_site_avg2)),2); // 孔位平均值
            }else{
                $data['design']['hole_site_avg'] = 0; // 孔位平均值
            }
            if(sizeof(array_sum($hole_site_percent2))){
                $data['design']['hole_site_percent'] = round(array_sum($hole_site_percent2) / sizeof(array_sum($hole_site_percent2)),2); // 孔位合格率
            }else{
                $data['design']['hole_site_percent'] = 0; // 孔位合格率
            }
            if(sizeof(array_sum($aperture_avg2))){
                $data['design']['aperture_avg'] = round(array_sum($aperture_avg2) / sizeof(array_sum($aperture_avg2)),2); // 孔径平均值
            }else{
                $data['design']['aperture_avg'] = 0; // 孔径平均值
            }
            if(sizeof(array_sum($aperture_percent2))){
                $data['design']['aperture_percent'] = round(array_sum($aperture_percent2) / sizeof(array_sum($aperture_percent2)),2); // 孔径合格率
            }else{
                $data['design']['aperture_percent'] = 0; // 孔径合格率
            }
            if(sizeof(array_sum($pore_slant_avg2))){
                $data['design']['pore_slant_avg'] = round(array_sum($pore_slant_avg2) / sizeof(array_sum($pore_slant_avg2)),2); // 孔斜平均值
            }else{
                $data['design']['pore_slant_avg'] = 0; // 孔斜平均值
            }
            if(sizeof(array_sum($pore_slant_percent2))){
                $data['design']['pore_slant_percent'] = round(array_sum($pore_slant_percent2) / sizeof(array_sum($pore_slant_percent2)),2); // 孔斜合格率
            }else{
                $data['design']['pore_slant_percent'] = 0; // 孔斜合格率
            }
        }


        /**
         * 全部
         */
        $data['all']['sampling_quantity'] = array_sum($sampling_quantity); // 抽检数量
        if(sizeof(array_sum($hole_depth_avg))){
            $data['all']['hole_depth_avg'] = round(array_sum($hole_depth_avg) / sizeof(array_sum($hole_depth_avg)),2); // 孔深平均值
        }else{
            $data['all']['hole_depth_avg'] = 0; // 孔深平均值
        }
        if(sizeof(array_sum($hole_depth_percent))){
            $data['all']['hole_depth_percent'] = round(array_sum($hole_depth_percent) / sizeof(array_sum($hole_depth_percent)),2); // 孔深合格率
        }else{
            $data['all']['hole_depth_percent'] = 0; // 孔深合格率
        }
        if(sizeof(array_sum($hole_depth_avg))){
            $data['all']['hole_site_avg'] = round(array_sum($hole_site_avg) / sizeof(array_sum($hole_site_avg)),2); // 孔位平均值
        }else{
            $data['all']['hole_site_avg'] = 0; // 孔位平均值
        }
        if(sizeof(array_sum($hole_site_percent))){
            $data['all']['hole_site_percent'] = round(array_sum($hole_site_percent) / sizeof(array_sum($hole_site_percent)),2); // 孔位合格率
        }else{
            $data['all']['hole_site_percent'] = 0; // 孔位合格率
        }
        if(sizeof(array_sum($aperture_avg))){
            $data['all']['aperture_avg'] = round(array_sum($aperture_avg) / sizeof(array_sum($aperture_avg)),2); // 孔径平均值
        }else{
            $data['all']['aperture_avg'] = 0; // 孔径平均值
        }
        if(sizeof(array_sum($aperture_percent))){
            $data['all']['aperture_percent'] = round(array_sum($aperture_percent) / sizeof(array_sum($aperture_percent)),2); // 孔径合格率
        }else{
            $data['all']['aperture_percent'] = 0; // 孔径合格率
        }
        if(sizeof(array_sum($pore_slant_avg))){
            $data['all']['pore_slant_avg'] = round(array_sum($pore_slant_avg) / sizeof(array_sum($pore_slant_avg)),2); // 孔斜平均值
        }else{
            $data['all']['pore_slant_avg'] = 0; // 孔斜平均值
        }
        if(sizeof(array_sum($pore_slant_percent))){
            $data['all']['pore_slant_percent'] = round(array_sum($pore_slant_percent) / sizeof(array_sum($pore_slant_percent)),2); // 孔斜合格率
        }else{
            $data['all']['pore_slant_percent'] = 0; // 孔斜合格率
        }
        return ['code'=>1,'excavate_data'=>$data,'msg'=>'排水孔统计数据'];
    }



}