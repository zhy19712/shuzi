<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 17:15
 */
//内部设备设施管理,绝缘安全工器具
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class InsulatinginnerModel extends Model
{
    protected $name = 'safety_insulating_equipment_inner';

    /*
     * 添加新的绝缘工器具文件
     */
    public function insertInsulatinginner($param)
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
     * 编辑绝缘工器具文件
    */
    public function editInsulatinginner($param)
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
     * 删除绝缘工器具文件
    */
    public function delInsulatinginner($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条绝缘工器具文件
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
     * 获取版本日期,excel的导入日期
     */
    public function getVersion()
    {
        return $this->group('input_time')->column('input_time');
    }
}