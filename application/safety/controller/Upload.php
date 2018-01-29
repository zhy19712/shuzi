<?php

namespace app\safety\controller;
use app\admin\controller\Base;
use app\safety\model\ResponsibilityModel;
use app\safety\model\SafetyGoalAnualModel;
use app\safety\model\SafetyGoalGeneralModel;

class Upload extends Base
{
    public function uploadResponsibility(){
        $responsibility = new ResponsibilityModel();
        $name = request()->param('name');
        $year = request()->param('year');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/responsibility');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'name' => $name,
                'filename' => $filename,
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'year' => $year,
                'path' => $path,
                'remark' => $remark,
                'dept' => session('dept')
            ];
            $flag = $responsibility->insertResponsibility($data);
            return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
        }else{
            echo $file->getError();
        }
    }

    public function uploadSafeyGoalAnual(){
        $responsibility = new SafetyGoalAnualModel();
        $name = request()->param('name');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/anual');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/acceptance/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'name' => $name,
                'filename' => $filename,
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'path' => $path,
                'remark' => $remark
            ];
            $flag = $responsibility->insertSafetyGoalAnual($data);
            return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
        }else{
            echo $file->getError();
        }
    }

    public function uploadSafeyGoalGeneral(){
        $responsibility = new SafetyGoalGeneralModel();
        $name = request()->param('name');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/general');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/acceptance/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'name' => $name,
                'filename' => $filename,
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'path' => $path,
                'remark' => $remark
            ];
            $flag = $responsibility->insertSafetyGoalGeneral($data);
            return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
        }else{
            echo $file->getError();
        }
    }
}