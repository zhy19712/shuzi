<?php
/**
 * Created by PhpStorm.
 * User: zhangchuan
 * Date: 2017/12/18
 * Time: 13:59
 */

namespace app\admin\model;


use think\Model;

class DivideModel extends Model
{
    protected  $name = 'project_divide';

    /**
     * [getNodeInfo 获取工程划分4级节点树结构数据]
     *
     */
    public function getNodeInfo()
    {
        $result = $this->field('id,name,pid')->select();
        $str = "";

        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['name'].'"';

            $str .= '},';
        }

        return "[" . substr($str, 0, -1) . "]";
    }

    /**
     * [getNodeInfo 获取工程划分5级节点树结构数据的前4级]
     *
     */
    public function getNodeInfo_4()
    {
        $result = $this->field('id,name,pid')->select();
        $str = "";

        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['name'].'"';

            $str .= '},';
        }

        return $str;
    }




    /**
     * 插入新的节点
     */
    public function insertNode($param)
    {
        try{
            $result = $this->validate('ProjectDivideValidate')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '节点添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑节点信息
     */
    public function editNode($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '节点信息编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [节点删除]
     */
    public function delNode($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '节点删除成功'];
    }

    /**
     * 根据id获取节点
     */
    public function getOneNode($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * [getAll 获取全部信息]
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
    //getAll by id
    public function getAllbyID($id)
    {
        return $this->where('id', $id)->select();
    }





    //递归获取当前节点的所有子节点
    public function cateTree($id){
        $res=$this->select();
        if($res){
            $result=$this->sort($res, $id);
            return $result;
        }
    }
    public function sort($data,$id,$level=0){
       static $arr=array();
        foreach ($data as $key=>$value){
            if($value['pid'] == $id){
                $value["level"]=$level;
                $arr[]=$value;
                $this->sort($data,$value['id'],$level+1);
            }
        }
        return $arr;
    }





}