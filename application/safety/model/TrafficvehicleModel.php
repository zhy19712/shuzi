<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 17:44
 */
//交通车辆
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class TrafficvehicleModel extends Model
{
    protected $name = 'safety_vehicle';

    /*
      * 添加新的交通车辆文件
     */
    public function insertTrafficvehicle($param)
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
     * 编辑交通车辆文件
    */
    public function editTrafficvehicle($param)
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
     * 删除交通车辆文件
    */
    public function delTrafficvehicle($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条交通车辆文件
    */
    public function getOne($id)
    {

        return $this->where('id', $id)->find();

    }

    /*
     * 获取交通车辆的版本日期,excel的导入日期
     */
    public function getVersion($param)
    {
//        return $this->field('input_time')->order('id desc')->select();
        return $this->where('selfid',$param)->group('input_time')->column('input_time');
    }

    /*
     * 交通车辆批量导出的文件列表id
     */
    public function getList($idArr)
    {
        $data = [];
        foreach($idArr as $v){
            $data[] = $this->getOne($v);
        }
        return $data;
    }
}