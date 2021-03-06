<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 17:44
 */
//个人防护装备
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class PersonalequipmentModel extends Model
{
    protected $name = 'safety_personal_equipment';

    /*
      * 添加新的个人防护装备文件
     */
    public function insertPersonalequipment($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 编辑个人防护装备文件
    */
    public function editPersonalequipment($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 删除个人防护装备文件
    */
    public function delPersonalequipment($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条个人防护装备文件
    */
    public function getOne($id)
    {

        return $this->where('id', $id)->find();

    }

    /*
     * 批量导出时候的数组处理
     */
    public  function getList($idArr)
    {
        $data = [];
        foreach($idArr as $v){
            $data[] = $this->getOne($v);
        }
        return $data;
    }

    /*
     * 查看所有的id值
     */
    public  function getallid()
    {
        return $this->group('id')->column('id');
    }

    /*
     * 根据条件查询全选条数
     */
    public  function getallcount($param)
    {
        return $this->where($param)->count('id');
    }

    /*
     * 获取个人防护设备文件的版本日期,excel的导入日期
     */
    public function getVersion($param)
    {
//        return $this->field('input_time')->order('id desc')->select();
        return $this->where('selfid',$param)->group('input_time')->column('input_time');
    }
}