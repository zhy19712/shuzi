<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 18:54
 */
//作业安全，消防安全管理
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class FiremanagementModel extends Model
{
    protected $name = 'safety_fire_management';


    /*
     * 添加新的消防安全管理信息
     */
    public function insertFiremanagement($param)
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
     * 编辑消防安全管理信息
     */
    public function editFiremanagement($param)
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
     * 删除消防安全管理信息
     */
    public function delFiremanagement($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条消防安全管理信息
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
     * 获取消防安全管理信息的版本日期,excel的导入日期
     */
    public function getVersion($param)
    {
        return $this->where('selfid',$param)->group('input_time')->column('input_time');
    }
}