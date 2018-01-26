<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/18
 * Time: 10:24
 */

namespace app\admin\model;
use app\quality\model\ProjectAttachmentModel;
use think\Model;

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
                return ['code' => 1, 'data' =>$result, 'msg' => '工程信息添加成功'];
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
     * 关联删除其他表中与此条project有联系的数据记录和文件
     * project_attachment
     * project_hunningtu
     * project_kaiwa
     * project_zhihu
     * project_zhihu_maogan
     * @return [type] [description]
     */
    public function delProject($id)
    {
        $flag = [];
        $data = $this->where('id',$id)->find();
        if($data['cate'] == '开挖'){
            $kaiwa = new KaiwaModel();
            $flag = $kaiwa->delKaiwaBuUid($id);
        }else if($data['cate'] == '支护'){
            $zhihu = new ZhihuModel();
            $flag = $zhihu->delZhihuByUid($id);
        }else if($data['cate'] == '混凝土'){
            $hunningtu = new HunningtuModel();
            $flag = $hunningtu->delHunningtuByUid($id);
        }
        if($flag['code'] == 1){
            $attchment = new ProjectAttachmentModel();
            $attFlag = $attchment->delAttachmentByPidUid($data['pid'],$data['id']);
            if($attFlag['code'] == 1){
                $bol = $this->where('id', $id)->delete();
                if($bol){
                    return ['code' => 1, 'data' => '', 'msg' => '工程信息删除成功'];
                }
                return ['code' => 1, 'data' => '', 'msg' => '工程信息删除失败'];
            }
            return ['code' => 1, 'data' => '', 'msg' => $attFlag['msg']];
        }
        return ['code' => 0, 'data' => '', 'msg' => $flag['msg']];
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
     * [根据条件获取所有的单元工程数量]
     */
    public function getAllProject($where)
    {
        return $this->where($where)->count();
    }
    /**
     * [ 根据条件获取工程列表信息]
     */
    public function getProjectByWhere($map, $Nowpage, $limits)
    {
        return $this->where($map)->page($Nowpage, $limits)->order('id desc')->select();
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

    /**
     * 根据pid删除project
     */
    public function delProjectByPid($pid){
        $flag = [];
        $idArr = $this->whereIn('pid',$pid)->column('id');
        if(count($idArr) == 0){
            return ['code' => 1, 'data' => '', 'msg' => '不包含project'];
        }
        foreach($idArr as $k=>$v){
            $flag = $this->delProject($v);
            if($flag['code'] == 0){
                break;
            }
        }
        return ['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']];
    }

}