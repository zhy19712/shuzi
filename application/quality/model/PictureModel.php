<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/11
 * Time: 10:37
 */
namespace app\quality\model;

use think\exception\PDOException;
use think\Model;
use think\Db;

class PictureModel extends Model
{
    protected $name = 'quality_model_picture';
    //自动写入创建、更新时间 insertGetId和update方法中无效，只能用于save方法
    protected $autoWriteTimestamp = true;

    public function insertTb($param)
    {
        try {
            $result = $this->allowField(true)->save($param);
            if (false === $result) {
                return ['code' => -1, 'msg' => $this->getError()];
            } else {
                return ['code' => 1, 'msg' => '关联成功'];
            }
        } catch (PDOException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
    }

    public function editTb($param)
    {
        try {
            $result = $this->allowField(true)->save($param, ['id' => $param['id']]);
            if (false === $result) {
                return ['code' => -1, 'msg' => $this->getError()];
            } else {
                return ['code' => 1, 'msg' => '编辑成功'];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function getAllName($id,$search_name='')
    {
        // type 和 picture_type  1工程划分模型 2 建筑模型 3三D模型
        // 用于初始化时 选中(回显) 之前关联过的模型图的主键
        $picture_id = Db::name('quality_model_picture_relation')->where(['type'=>1,'relevance_id'=>$id])->value('picture_id');
        // 全部的列表
        if(empty($search_name)){
            $data = $this->where('picture_type',1)->column('id as picture_id,picture_number,picture_name');
        }else{
            $data = $this->where(['picture_type'=>1,'picture_name'=>['like','%'.$search_name.'%']])->column('id as picture_id,picture_number,picture_name');
        }
        $str = '';
        foreach ($data as $v) {
            $str .= '{ "id": "' . $v['picture_id'] . '", "pId":"' . 0 . '", "name":"' . $v['picture_name'] . '"' . ',"picture_number":"'.$v['picture_number'].'"' . ',"picture_id":"'.$v['picture_id'].'"';
            $str .= '},';
        }
        $data['one_picture_id'] =  $picture_id;
        $data['str'] =  "[" . substr($str, 0, -1) . "]";
        return $data;
    }

    public function getRemarkTb($id)
    {
        $remark = $this->where(['id'=>$id])->value('remark');
        return $remark;
    }

    /**
     * 获取全部的模型
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllModelPic()
    {
        $data = $this->where("picture_type = 1")->group("picture_number,picture_name")->order("id asc")->field("picture_number,picture_name")->select();
        return $data;
    }

    /**
     * 获取模型全部的统计数据
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllCountUnit()
    {
        $unit_data = Db::name('quality_unit')->alias('u')
            ->join('quality_model_picture_relation r', 'r.relevance_id = u.id', 'left')
            ->join('quality_model_picture p', 'p.id = r.picture_id', 'left')
            ->where('r.type = 1')
            ->where("p.picture_type = 1")
            ->field("p.picture_number,p.picture_name,u.EvaluateResult")->order("u.id asc")->select();
        return $unit_data;
    }

    /**
     * 根据picture_number模型图编号查询单元工程下对应的信息
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUnitInfo($picture_number)
    {
        $unit_info = Db::name('quality_unit')->alias('u')
            ->join('quality_model_picture_relation r', 'r.relevance_id = u.id', 'left')
            ->join('quality_model_picture p', 'p.id = r.picture_id', 'left')
            ->where('r.type = 1')
            ->where("p.picture_type = 1")
            ->where("p.picture_number",$picture_number)
            ->field("u.site,u.coding,u.hinge,u.quantities,u.en_type,u.ma_bases,u.su_basis,u.el_start,u.el_cease,u.pile_number,u.start_date,u.completion_date,u.en_type")->find();
        return $unit_info;
    }

    /**
     * 根据picture_number模型图编号查询对应的工序信息，获取归属工程类型
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEnType($picture_number)
    {
        $en_type =  Db::name('quality_unit')->alias('u')
            ->join('quality_model_picture_relation r', 'r.relevance_id = u.id', 'left')
            ->join('quality_model_picture p', 'p.id = r.picture_id', 'left')
            ->where("r.type = 1")
            ->where("p.picture_type = 1")
            ->where("p.picture_number",$picture_number)
            ->field("u.en_type,u.division_id")->find();
        return $en_type;
    }

    /**
     * 根据归属工程编号查询工序号
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProcessInfo($en_type)
    {
        $processinfo = Db::name("materialtrackingdivision")->alias('a')
//            ->join('quality_form_info q', 'q.ProcedureId=a.id', 'left')
            ->where(["pid"=>$en_type["en_type"],"type"=>3])
//            ->field("a.id,a.pid,a.name,q.id as form_id,q.form_name as form_name")
            ->field("a.id,a.pid,a.name")
            ->select();
        return $processinfo;
    }

    /**
     * 根据归属工程编号查询控制点信息
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProcessInfoList($par)
    {
        $processinfo_list = Db::name("quality_division_controlpoint_relation")->alias('a')
            ->join('controlpoint b', 'a.control_id=b.id', 'left')
            ->where($par)
            ->field('a.id as cpr_id,b.code,b.name,a.status,a.division_id,a.ma_division_id,a.control_id')
            ->select();
        return $processinfo_list;
    }
}