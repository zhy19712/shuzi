<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class UserModel extends Model
{
    protected $name = 'admin';

    /**
     * 根据搜索条件获取用户列表信息
     */
    public function getUsersByWhere($map, $Nowpage, $limits)
    {
        return $this->field('think_admin.*,title')->join('think_auth_group', 'think_admin.groupid = think_auth_group.id')
            ->where($map)->page($Nowpage, $limits)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */
    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入管理员信息
     * @param $param
     */
    public function insertUser($param)
    {
        try{
            $result = $this->validate('UserValidate')->allowField(true)->save($param);
            if(false === $result){            
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('uid'),session('username'),'用户【'.$param['username'].'】添加成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 编辑管理员信息
     * @param $param
     */
    public function editUser($param)
    {
        try{
            $result =  $this->validate('UserValidate')->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){            
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                writelog(session('uid'),session('username'),'用户【'.$param['username'].'】编辑成功',1);
                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }


    /**
     * 删除管理员
     * @param $id
     */
    public function delUser($id)
    {
        try{

            $this->where('id', $id)->delete();
            Db::name('auth_group_access')->where('uid', $id)->delete();
            writelog(session('uid'),session('username'),'用户【'.session('username').'】删除管理员成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除用户成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    public function getData($data)
    {
        //获取Datatables发送的参数 必要
        $draw = $data['draw'];    //这个值直接返回给前台


        //搜索框
        $search = trim($data['search']['value']);    //获取前台传过来的过滤条件
        if(strlen($search) > 0) {
            $where['id|username'] = array('like','%'.$search.'%');
        }
        //定义查询数据总记录数sql
        $recordsTotal = $this->count();
        //定义过滤条件查询过滤后的记录数sql
        $recordsFiltered =  $this->where($where)->count();
        //排序条件
        $orderArr = [1=>'id', 2=>'username'];
        //获取要排序的字段
        $orderField = (empty($orderArr[$data['order']['0']['column']])) ? 'id' : $orderArr[$data['order']['0']['column']];
        //需要空格,防止字符串连接在一块
        $order = $orderField.' '.$data['order']['0']['dir'];
        //按条件过滤找出记录
        $result = [];
        //备注:$data['start']起始条数    $data['length']查询长度
        $result = $this->field('id,fromnickname,content,msgtype,time')
            ->where($where)
            ->order($order)
            ->limit(intval($data['start']), intval($data['length']))
            ->select();
        //处理数据
        if(!empty($result)) {
            foreach ($result as $key => $value) {
                $result[$key]['time'] = date("Y-m-d H:i:s",$value['time']);
                $result[$key]['recordsFiltered'] = $recordsFiltered;
            }
        }
        //拼接要返回的数据
        $list = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered"=>intval($recordsFiltered),
            "data" => $result,
        );
        return $list;
    }


}