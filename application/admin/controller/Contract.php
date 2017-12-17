<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/13
 * Time: 8:53
 */

namespace app\admin\controller;
use app\admin\model\ContractModel;
use think\Db;

class contract extends Base
{
    public function index()
    {


        return $this->fetch();
    }

    /**
     * [add_group 添加合同信息]
     */
    public function contractAdd()
    {
        $contract = new ContractModel();

        if(request()->isAjax()){
            if(empty($param['id']))
            {
                $param = input('post.');
                $flag = $contract->insertContract($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $contract->editContract($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }


        }
        return $this->fetch();
    }

    /**
     * [UserDel 删除合同信息]
     * @return [type] [description]
     */
    public function contractDel()
    {
        $id = input('param.id');
        $contract = new ContractModel();
        $flag = $contract->delContract($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



}