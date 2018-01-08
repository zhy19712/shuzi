<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/26
 * Time: 9:34
 */

namespace app\admin\controller;


use app\admin\model\ProcedureListModel;
use app\admin\model\ProcedureListSublistModel;
use app\admin\model\ProcedureModel;

class Procedure extends Base
{
    public function index()
    {
        $procedure = new ProcedureModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function procedureAdd()
    {
        $procedure = new ProcedureModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $procedure->insertProcedure($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $procedure->editProcedure($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function procedureDel()
    {
        $procedure = new ProcedureModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $procedure->delProcedure($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function procedureListEdit()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure_list->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function procedureListAdd()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $procedure_list->insertProcedureList($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $procedure_list->editProcedureList($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function procedureListDel()
    {
        $procedure_list = new ProcedureListModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $procedure_list->delProcedureList($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function procedureListSublistEdit()
    {
        $procedure_list_sublist = new ProcedureListSublistModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $procedure_list_sublist->getOne($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }

    public function procedureListSublistAdd()
    {
        $procedure_list_sublist = new ProcedureListSublistModel();
        if(request()->isAjax()){
            $param = input('post.');
            if(empty($param['id']))
            {
                $flag = $procedure_list_sublist->insertProcedureListSublist($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $procedure_list_sublist->editProcedureListSublist($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    public function procedureListSublistDel()
    {
        $procedure_list_sublist = new ProcedureListSublistModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $procedure_list_sublist->delProcedureListSublist($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}