<?php

namespace app\admin\controller;
use app\admin\model\ProcedureAttachmentModel;
use app\admin\model\ProcedureModel;
use app\admin\model\ProjectStageModel;
use app\admin\model\PrototypeAttachmentModel;
use app\admin\model\PrototypeModel;
use app\admin\model\ReformAttachmentModel;
use app\admin\model\ReformModel;
use think\Controller;
use think\File;
use think\Request;
use app\admin\model\QCAttachmentModel;

class Upload extends Base
{
	//图片上传
    public function upload(){
       $file = request()->file('file');
       $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/images');
       if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }

    //会员头像上传
    public function uploadface(){
       $file = request()->file('file');
       $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/face');
       if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();

        }
    }

    //文件上传
    public function uploadfile(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/acceptance');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }

    public function uploadtest(){
        $request_body = request()->param('sex');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/attachment');
        $data = json_decode($request_body);;
        if($info){
            return json(['data' => $request_body]);
        }else{
            echo $file->getError();
        }
    }

    public function uploadQC(){
        $qc = new QCAttachmentModel();
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $group_id = request()->param('group_id');
        $revision = request()->param('revision');
        $publish_date = request()->param('publish_date');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/qc');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/qc/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'name' => $filename,
                    'revision' => $revision,
                    'group_id' => $group_id,
                    'table_name' => $table_name,
                    'publish_date' => $publish_date
                ];
                $flag = $qc->insertAttachment($data);
                $data_newer = $qc->getImageId($group_id, $table_name);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg'], 'id' => $data_newer['id']]);
            }else{
                $data_older = $qc->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'name' => $filename,
                    'revision' => $revision,
                    'group_id' => $group_id,
                    'table_name' => $table_name,
                    'publish_date' => $publish_date
                ];
                $flag = $qc->editAttachment($data);
                $data_newer = $qc->getImageId($group_id, $table_name);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg'], 'id' => $data_newer['id']]);
            }
        }else{
            echo $file->getError();
        }
    }

    public function uploadPrototype(){
        $prototype = new PrototypeModel();
        $id = request()->param('uid');
        $name = request()->param('uname');
        $year = request()->param('year');
        $season = request()->param('season');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/Prototype');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/prototype/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'year' => $year,
                    'season' => $season,
                    'name' => $name,
                    'filename' => $filename
                ];
                $flag = $prototype->insertPrototype($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else{
//                $data_older = $prototype->getOne($id);
//                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'year' => $year,
                    'season' => $season,
                    'name' => $name,
                    'filename' => $filename
                ];
                $flag = $prototype->editPrototype($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }


    public function uploadPrototypeAttachment(){
        $prototype = new PrototypeAttachmentModel();
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $group_id = request()->param('group_id');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/Prototype/Attachment');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/prototype/Attachment/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'table_name' => $table_name,
                    'group_id' => $group_id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark,
                    'name' => $filename
                ];
                $flag = $prototype->insertAttachment($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else{
//                $data_older = $prototype->getOne($id);
//                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'table_name' => $table_name,
                    'group_id' => $group_id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark,
                    'name' => $filename
                ];
                $flag = $prototype->editAttachment($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    //标准工艺文件上传
    public function uploadProcedure(){
        $procedure = new ProcedureModel();
        $id = request()->param('uid');
        $name = request()->param('uname');
        $year = request()->param('year');
        $season = request()->param('season');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/Procedure');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/procedure/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'year' => $year,
                    'season' => $season,
                    'name' => $name,
                    'filename' => $filename
                ];
                $flag = $procedure->insertProcedure($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'year' => $year,
                    'season' => $season,
                    'name' => $name,
                    'filename' => $filename
                ];
                $flag = $procedure->editProcedure($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    //标准工艺附件
    public function uploadProcedureAttachment(){
        $procedure = new ProcedureAttachmentModel();
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $group_id = request()->param('group_id');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/Procedure/Attachment');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/procedure/Attachment/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'table_name' => $table_name,
                    'group_id' => $group_id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark,
                    'name' => $filename
                ];
                $flag = $procedure->insertAttachment($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else{
//                $data_older = $procedure->getOne($id);
//                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'table_name' => $table_name,
                    'group_id' => $group_id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark,
                    'name' => $filename
                ];
                $flag = $procedure->editAttachment($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    public function uploadProjectStage(){
        $stage = new ProjectStageModel();
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $name = request()->param('uname');
        $status = request()->param('status');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/StageAcceptance');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/StageAcceptance/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'table_name' => $table_name,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'name' => $name,
                    'status' => $status,
                    'filename' => $filename
                ];
                $flag = $stage->insertStage($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else{
//                $data_older = $prototype->getOne($id);
//                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'table_name' => $table_name,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'name' => $name,
                    'status' => $status,
                    'filename' => $filename
                ];
                $flag = $stage->editStage($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    public function uploadReform()
    {
        $reform = new ReformModel();
        $file = request()->file('file');
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/Reform');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/Reform/' . str_replace("\\","/",$temp);
            if($table_name == 'jc'){
                $data = [
                    'id' => $id,
                    'reform_image_path' => $path
                ];
                $flag = $reform->editReform($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else if($table_name == 'jcbhg'){
                $data = [
                    'id' => $id,
                    'unqualified_image_path' => $path
                ];
                $flag = $reform->editReform($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    public function uploadReformAttachment()
    {
        $attachment = new ReformAttachmentModel();
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
        $group_id = request()->param('group_id');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/Reform/Attachment');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/Reform/Attachment/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'path' => $path,
                'name' => $filename,
                'group_id' => $group_id,
                'table_name' => $table_name
            ];
            $flag = $attachment->insertAttachment($data);
            return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
        }else{
            echo $file->getError();
        }
    }




    //视频上传V
    public function uploadVideo(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/video');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }
}