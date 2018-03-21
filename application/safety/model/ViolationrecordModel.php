<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 18:54
 */
//作业安全，反违章记录
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class ViolationrecordModel extends Model
{
    protected $name = 'safety_violation_record';


    /*
     * 添加新的反违章记录文件
     */
    public function insertViolationrecord($param)
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
     * 编辑反违章记录验收文件
     */
    public function editViolationrecord($param)
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
     * 删除反违章记录文件
     */
    public function delViolationrecord($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条反违章记录文件
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
     * 获取反违章记录的版本日期,excel的导入日期
     */
    public function getVersion($param)
    {
        return $this->where('selfid',$param)->group('input_time')->column('input_time');
    }
}