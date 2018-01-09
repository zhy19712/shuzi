<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:35
 */

namespace app\admin\controller;


use app\admin\model\ReformModel;

class Reform extends Base
{
    public function index()
    {
        $reform = new ReformModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $reform->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function ReformAdd()
    {
        $Reform = new ReformModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $Reform->insertReform($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $Reform->editReform($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function ReformDel()
    {
        $Reform = new ReformModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $Reform->delReform($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}