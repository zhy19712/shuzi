<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/18
 * Time: 10:24
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class ProjectModel extends Model
{
    protected $name = 'project';

    /**
     * 单元工程验收批次插入信息
     * @param $param
     */
    public function insertProject($param)
    {
        try{
            $result = $this->validate('ProjectValidate')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '工程信息添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * 单元工程验收批次编辑信息
     * @param $param
     */
    public function editProject($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '工程信息编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [del_article 单元工程验收批次删除]
     * @return [type] [description]
     */
    public function delProject($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '工程信息删除成功'];
    }


    /**
     * [获取全部信息]
     */
    public function getAll()
    {
        return $this->order('id asc')->select();
    }
    //getAll by pid
    public function getAllbyPID($pid)
    {
        return $this->where('pid', $pid)->select();
    }
    //getAll by pid and primary
    public function getAllbyPIDandPrimary($pid)
    {
        $where['pid'] = $pid;
        $where['primary'] = '是';
        return $this->where($where)->select();
    }


    /**
     * 根据id获取信息
     * @param $id
     */
    public function getOneProject($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * [getNodeInfo 获取工程划分5级节点树结构数据的第5级]
     *
     */
    public function getNodeInfo_5()
    {
        $result = $this->field('id,name,pid,cate')->select();
        $str = "";

        foreach($result as $key=>$vo){
            $str .= '{ "uid": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "cate":"' . $vo['cate'] . '", "name":"' . $vo['name'].'"';

            $str .= '},';
        }

        return $str;
    }

}