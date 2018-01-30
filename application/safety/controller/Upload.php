<?php

namespace app\safety\controller;
use app\admin\controller\Base;
use app\safety\model\ResponsibilityModel;
use app\safety\model\SafetyGoalAnualModel;
use app\safety\model\SafetyGoalGeneralModel;

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
        $name = request()->param('name');
        $dept = request()->param('dept');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/responsibility');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/responsibility/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'name' => $name,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'dept' => $dept,
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $responsibility->insertResponsibility($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $responsibility->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'name' => $name,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'dept' => $dept,
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $responsibility->insertResponsibility($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }
}