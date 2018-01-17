<?php

namespace app\quality\controller;
use app\admin\controller\Base;
use app\quality\model\ProcedureAttachmentModel;
use app\quality\model\ProcedureModel;
use app\quality\model\ProjectStageModel;
use app\quality\model\PrototypeAttachmentModel;
use app\quality\model\PrototypeModel;
use app\quality\model\ReformAttachmentModel;
use app\quality\model\ReformModel;
use app\quality\model\ConstructionModel;
use think\Controller;
use think\File;
use think\Request;
use app\quality\model\QCAttachmentModel;

class Upload extends Base
{
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
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/ProjectStage');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/ProjectStage/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(!empty($id))
            {
                $data = [
                    'id' => $id,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'status' => '已上传',
                    'filename' => $filename
                ];
                $flag = $stage->editStage($data);
                return json(['code' => $flag['code'], 'path' => $path, 'msg' => $flag['msg']]);
            }else{
                return json(['msg' => 'error!']);
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
        $video = new ConstructionModel();
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/video');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/Reform/Attachment/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'date' => date("Y-m-d H:i:s"),
                'path' => $path,
                'name' => $filename
            ];
            $flag = $video->insertVideo($data);
            return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
        }else{
            echo $file->getError();
        }
    }
}