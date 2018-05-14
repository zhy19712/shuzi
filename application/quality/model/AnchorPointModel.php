<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/11
 * Time: 10:26
 */
namespace app\quality\model;

use think\exception\PDOException;
use think\Model;
use think\Db;

class AnchorPointModel extends Model
{
    protected $name = 'quality_anchor_point';
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
                return ['code' => 1,'anchor_point_id'=>$last_insert_id, 'msg' => '添加成功'];
            }
        } catch (PDOException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
    }

    public function editTb($param,$type=1)
    {
        try {
            $result = $this->allowField(true)->save($param, ['id' => $param['id']]);
            if (false === $result) {
                return ['code' => -1, 'msg' => $this->getError()];
            } else {
                if($type==1){
                    // 上传成功后，返回文件主键和地址
                    $data = Db::name('attachment')->where('id',$param['attachment_id'])->column('id as attachment_id,filepath');
                    return ['code' => 1,'data'=>$data, 'msg' => '上传成功'];
                }
                return ['code' => 1,'anchor_point_id'=>$param['id'], 'msg' => '编辑成功'];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function deleteTb($id)
    {
        try {
            // 关联删除锚点下的 附件
            $data = $this->getAttachmentId($id);
            $id_arr = explode(',',$data);
            Db::name('attachment')->where(['id'=>['in',$id_arr]])->delete();

            $this->where('id', $id)->delete();
            return ['code' => 1, 'msg' => '删除成功'];
        } catch (PDOException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
    }

    public function getAttachmentId($id)
    {
        $data = $this->where('id',$id)->value('attachment_id');
        return $data;
    }

    public function getAnchorTb($name='')
    {
        if($name){
            $data = Db::name('quality_anchor_point')->alias('p')
                ->where(['p.picture_type'=>1,'p.anchor_name'=>$name])
                ->field('p.id as anchor_point_id,p.picture_number,p.anchor_name,p.component_name,p.user_name,p.coordinate_x,p.coordinate_y,p.coordinate_z,p.remark,p.attachment_id')
                ->select();
            if(sizeof($data) < 1){
                return $data;
            }
            $id_arr = explode(',',$data[0]['attachment_id']);
            $attachment = Db::name('attachment')->where(['id'=>['in',$id_arr]])->field('id as attachment_id,filepath')->select();
            // 图片放一起,文件放一起
            $img = ['jpg','jpeg','png','gif','bmp','pcx','emf','tga','tif','rle'];
            $data['img_arr'] = $data['file_arr'] = '';
            foreach ($attachment as $v){
                $ex = get_extension($v['filepath']);
                if(in_array($ex,$img)){
                    $data['img_arr'][] = $v;
                }else{
                    $data['file_arr'][] = $v;
                }
            }
        }else{
            $data = Db::name('quality_anchor_point')->alias('p')
                ->where(['p.picture_type'=>1])
                ->field('p.id as anchor_point_id,p.picture_number,p.anchor_name,p.component_name,p.user_name,p.coordinate_x,p.coordinate_y,p.coordinate_z,p.remark')
                ->select();
        }
        return $data;
    }

    public function getAnchorByName($name)
    {
        return  $this->where('anchor_name',$name)->value('id');
    }

    public function delAnchorPointAttachment($id,$attachment_id)
    {
        $old_attachment_id = $this->getAttachmentId($id);
        $new_attachment_id = str_replace(','.$attachment_id,'',$old_attachment_id);
        $data['id'] = $id;
        $data['attachment_id'] = $new_attachment_id;
        $this->editTb($data);
        Db::name('attachment')->where(['id'=>['eq',$attachment_id]])->delete();
    }
}