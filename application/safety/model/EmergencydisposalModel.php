<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/18
 * Time: 15:33
 */
//应急处置
namespace app\safety\model;

use think\exception\PDOException;
use think\Model;

class EmergencydisposalModel extends Model
{
    protected $name = 'safety_emergency_disposal';

    /*
     * 添加新的应急处置文件
     */
    public function insertEmergencydisposal($param)
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
     * 编辑应急处置文件
     */
    public function editEmergencydisposal($param)
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
     * 删除应急处置文件
     */
    public function delEmergencydisposal($id)
    {
        try{
            //文件名称保留其余清空
            $data = array();
            $data['name'] = " ";
            $data['filename'] = " ";
            $data['preplan_number'] = " ";
            $data['version_number'] = " ";
            $data['alternative_version'] = " ";
            $data['applicability'] = " ";
            $data['preplan_state'] = " ";
            $data['owner'] = " ";
            $data['date'] = " ";
            $data['remark'] = " ";
            $data['path'] = " ";

            $this->allowField(true)->save($data, ['id' => $id]);
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条应急处置文件
     */
    /**
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }
}