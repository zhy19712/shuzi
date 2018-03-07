<?php

namespace app\safety\controller;
use app\admin\controller\Base;
use app\safety\model\ResponsibilityModel;
use app\safety\model\RulesregulationsModel;
use app\safety\model\SafetyGoalAnualModel;
use app\safety\model\SafetyGoalGeneralModel;
use app\safety\model\StatutestdiModel;

class Upload extends Base
{
    public function uploadSafeyGoalAnual(){
        $anual = new SafetyGoalAnualModel();
        $id = request()->param('aid');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/anual');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/anual/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $anual->insertSafetyGoalAnual($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $anual->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $anual->editSafetyGoalAnual($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    public function uploadSafeyGoalGeneral(){
        $general = new SafetyGoalGeneralModel();
        $id = request()->param('gid');
        $year = request()->param('year');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/general');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/general/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'year' => $year,
                    'remark' => $remark
                ];
                $flag = $general->insertSafetyGoalGeneral($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $general->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'year' => $year,
                    'remark' => $remark
                ];
                $flag = $general->editSafetyGoalGeneral($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    public function uploadResponsibility(){
        $responsibility = new ResponsibilityModel();
        $id = request()->param('rid');
        $username = request()->param('rname');
        $dept = request()->param('dept');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/responsibility');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/responsibility/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                if(empty($username)){
                    $data = [
                        'name' => $filename,
                        'filename' => $filename,
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'dept' => $dept,
                        'path' => $path
                    ];
                }else{
                    $data = [
                        'name' => $filename,
                        'filename' => $filename,
                        'username' => $username,
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'path' => $path
                    ];
                }

                $flag = $responsibility->insertResponsibility($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $responsibility->getOne($id);
                unlink($data_older['path']);
                if(empty($username)){
                    $data = [
                        'id' => $id,
                        'name' => $filename,
                        'filename' => $filename,
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'dept' => $dept,
                        'path' => $path
                    ];
                }else{
                    $data = [
                        'id' => $id,
                        'name' => $filename,
                        'filename' => $filename,
                        'username' => $username,
                        'owner' => session('username'),
                        'date' => date("Y-m-d H:i:s"),
                        'path' => $path
                    ];
                }
                $flag = $responsibility->insertResponsibility($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 法规标准识别 -- 新增或修改
     * @return \think\response\Json
     * @author hutao
     */
    public function uploadSdi(){
        $sdi = new StatutestdiModel();
        $number = request()->param('number');
        $sdi_name = request()->param('sdi_name');
        $go_date = request()->param('go_date');
        $standard = request()->param('standard');
        $evaluation = request()->param('evaluation');
        $sid_user = request()->param('sid_user');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/statutesdi');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/statutesdi/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'number' => $number,
                    'sdi_name' => $sdi_name,
                    'go_date' => $go_date,
                    'standard' => $standard,
                    'evaluation' => $evaluation,
                    'sid_user' => $sid_user,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'sdi_date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $sdi->insertSdi($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $sdi->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'number' => $number,
                    'sdi_name' => $sdi_name,
                    'go_date' => $go_date,
                    'standard' => $standard,
                    'evaluation' => $evaluation,
                    'sid_user' => $sid_user,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'sdi_date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $sdi->editSdi($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 规章制度 -- 新增或修改
     * @return \think\response\Json
     * @author hutao
     */
    public function uploadRules(){
        $rules = new RulesregulationsModel();
        $number = request()->param('number');
        $rul_name = request()->param('rul_name');
        $go_date = request()->param('go_date');
        $standard = request()->param('standard');
        $evaluation = request()->param('evaluation');
        $rul_user = request()->param('rul_user');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/rules');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/rules/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'number' => $number,
                    'rul_name' => $rul_name,
                    'go_date' => $go_date,
                    'standard' => $standard,
                    'evaluation' => $evaluation,
                    'rul_user' => $rul_user,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'rul_date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $rules->insertRulation($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $rules->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'number' => $number,
                    'rul_name' => $rul_name,
                    'go_date' => $go_date,
                    'standard' => $standard,
                    'evaluation' => $evaluation,
                    'rul_user' => $rul_user,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'rul_date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $rules->editRulation($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }
}