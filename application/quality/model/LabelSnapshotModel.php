<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/11
 * Time: 10:35
 */
namespace app\quality\model;

use think\exception\PDOException;
use think\Model;
use think\Db;

class LabelSnapshotModel extends Model
{
    protected $name = 'quality_label_snapshot';
    //自动写入创建、更新时间 insertGetId和update方法中无效，只能用于save方法
    protected $autoWriteTimestamp = true;

    public function insertTb($param)
    {
        try {
            $result = $this->allowField(true)->save($param);
            $last_insert_id = $this->getLastInsID();
            if (false === $result) {
                return ['code' => -1, 'msg' => $this->getError()];
            } else {
                // 返回添加的日期
                $time = $this->where('')->value('create_time');
                return ['code' => 1,'label_snapshot_id'=>$last_insert_id,'label_snapshot'=>$param['label_snapshot'],'compress_base64'=>$param['base64_val'],'create_time'=>date('Y-m-d H:i:s',$time), 'msg' => '添加成功'];
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

    public function deleteTb($id)
    {
        try {
            $this->where('id', $id)->delete();
            return ['code' => 1, 'msg' => '删除成功'];
        } catch (PDOException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
    }

    public function getLabelSnapshotTb($type,$picture_number)
    {
        $data = Db::name('quality_label_snapshot')
            ->where(['picture_type'=>1,'type'=>$type,'picture_number'=>$picture_number])
            ->field('id as label_snapshot_id,label_snapshot,base64_val as compress_base64,FROM_UNIXTIME(create_time) as create_time,base64_val_url')->select();
        return $data;
//        return ['code'=>1,'data'=>$data,'msg'=>'图片的base64值'];
    }

    /**
     * 获取一条信息
     * @param $id
     * @throws \think\exception\DbException
     */
    public function getOne($id)
    {
        $data = $this->where("id",$id)->find();
        return $data;
    }
}