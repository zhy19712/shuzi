<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/4/18
 * Time: 17:30
 */

namespace app\admin\model;


use think\Db;
use think\exception\PDOException;
use think\Model;

class PictureRelationModel extends Model
{
    protected $name = 'quality_model_picture_relation';
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
                return ['code' => 1, 'msg' => '关联成功'];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }


    public function getAllNumber($id)
    {
        // 获取 工程划分 或者 单元工程段号 关联 的模型图
        // type 1工程划分模型 2 建筑模型 3三D模型
        $data = Db::name('quality_model_picture_relation')->alias('r')
            ->join('quality_model_picture p','r.picture_id = p.id','left')
            ->where(['r.type'=>1,'r.relevance_id'=>['in',$id]])->column('r.id,r.picture_id,p.picture_number,p.picture_name');
        $newData = ['id_arr'=>[],'picture_id'=>[],'picture_number_arr'=>[],'picture_name_arr'=>[]];
        foreach ($data as $v){
            $newData['id_arr'][] = $v['id']; // 关联表主键
            $newData['picture_id_arr'][] = $v['picture_id']; // 模型图主键
            $newData['picture_number_arr'][] = $v['picture_number']; // 模型图编号
            $newData['picture_name_arr'][] = $v['picture_name']; // 模型图名称
        }
        return $newData;
    }

    public function deleteRelation($id)
    {
        try{
            $this->where(['type'=>1,'relevance_id'=>['in',$id]])->delete();
            return ['code' => 1, 'msg' => '删除成功'];
        }catch(PDOException $e){
            return ['code' => -1,'msg' => $e->getMessage()];
        }
    }


}