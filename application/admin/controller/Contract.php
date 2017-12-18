<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/13
 * Time: 8:53
 */

namespace app\admin\controller;
use app\admin\model\ContractModel;
use app\admin\model\PartyModel;
use think\Db;

class contract extends Base
{
    public function index()
    {


        return $this->fetch();
    }

    /**
     * [add_group 添加合同信息(添加界面)]
     */
    public function contractAddUI()
    {
        if(request()->isAjax()){
            $party = new PartyModel();
            $first = $party->getFirstParties();
            $second = $party->getSecondParties();
            return json(['first' => $first, 'second' => $second, 'msg' => "success"]);
        }
        return $this->fetch();
    }



    /**
     * [add_group 添加合同信息(保存按钮)]
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


    public function contractEdit()
    {
        $contract = new ContractModel();
        $party = new PartyModel();

        if(request()->isAjax()){

            $param = input('post.');
            $data = $contract->getOneContract($param['id']);
            $first = $party->getFirstParties();
            $second = $party->getSecondParties();
            //   $nodeStr = $role->getNodeInfo();
            //    return json(['data' => $data, 'group' => $nodeStr, 'msg' => "success"]);
            return json(['data' => $data, 'first' => $first, 'second' => $second, 'msg' => "success"]);
        }
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