<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/7
 * Time: 14:01
 */

namespace app\safety\model;


use think\exception\PDOException;
use think\Model;

class SafetySdiNodeModel extends Model
{
    protected $name = 'safety_sdi_node';

    public function insertSdinode($param)
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

    public function editSdinode($param)
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

    public function delSdinode($id,$ptype)
    {
        try{
            // 1 法规标准识别 2 规章制度
            // 删除节点时，先删除该节点下的所有文件
            if($ptype == 1){
                $sdi = new StatutestdiModel();
                $bol = $sdi->delSdiByGroupId($id);
                if($bol['code'] != 1){
                    return $bol;
                }
            }else{
                $rules = new RulesregulationsModel();
                $bol = $rules->delRulesByGroupId($id);
                if($bol['code'] != 1){
                    return $bol;
                }
            }


            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getNodeInfo($id)
    {
        // 1法规标准识别2规章制度
        $result = $this->field('id,pname,pid')->where('ptype',$id)->select();
        $str = "";
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['pname'].'"';
            $str .= '},';
        }
        return "[" . substr($str, 0, -1) . "]";
    }

    /**
     * 根据id获取节点
     */
    public function getOneNode($id)
    {
        return $this->where('id', $id)->find();
    }

    //递归获取当前节点的所有子节点
    public function hasSubclass($id)
    {
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