<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/15
 * Time: 18:39
 */
//事故档案
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class AccidentfileModel extends Model
{
    protected $name = 'safety_accident_file';

    /*
    * 添加新的事故档案记录
    */
    public function insertAccidentfile($param)
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
}