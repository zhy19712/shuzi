<?php

namespace app\admin\controller;
use app\admin\model\UserModel;
use app\admin\model\UserType;
use think\Db;

class User extends Base
{

    /**
     * [index 用户列表]
     * @return [type] [description]
     */
    public function index(){


        return $this->fetch();
    }


    /**
     * [userAdd 添加用户]
     * @return [type] [description]
     */
    public function userAdd()
    {
        if(request()->isAjax()){

            $param = input('post.');
            $param['password'] = md5(md5($param['password']) . config('auth_key'));
            $user = new UserModel();
            $flag = $user->insertUser($param);
            $accdata = array(
                'uid'=> $user['id'],
                'group_id'=> $param['groupid'],
            );
            $group_access = Db::name('auth_group_access')->insert($accdata);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $role = new UserType();
        $this->assign('role',$role->getRole());
        return $this->fetch();
    }


    /**
     * [userEdit 编辑用户]
     * @return [type] [description]
     */
    public function userEdit()
    {
        $user = new UserModel();

        if(request()->isAjax()){

            $param = input('post.');
            if(empty($param['password'])){
                unset($param['password']);
            }else{
                $param['password'] = md5(md5($param['password']) . config('auth_key'));
            }
            $flag = $user->editUser($param);
            $group_access = Db::name('auth_group_access')->where('uid', $user['id'])->update(['group_id' => $param['groupid']]);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $role = new UserType();
        $this->assign([
            'user' => $user->getOneUser($id),
            'role' => $role->getRole()
        ]);
        return $this->fetch();
    }


    /**
     * [UserDel 删除用户]
     * @return [type] [description]
     */
    public function UserDel()
    {
        $id = input('param.id');
        $role = new UserModel();
        $flag = $role->delUser($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



    /**
     * [user_state 用户状态]
     * @return [type] [description]
     */
    public function user_state()
    {
        $id = input('param.id');
        $status = Db::name('admin')->where('id',$id)->value('status');//判断当前状态情况
        if($status==1)
        {
            $flag = Db::name('admin')->where('id',$id)->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = Db::name('admin')->where('id',$id)->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }

    }


    /**
     * [获取组织机构树所需数据]
     * @return [type] [description]
     */
    public function getTreeData()
    {
        $role = new UserType();
        $nodeStr = $role->getNodeInfo();
        return json($nodeStr);
    }

}