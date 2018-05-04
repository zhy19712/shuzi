<?php

namespace app\quality\controller;
use app\admin\controller\Base;
use app\quality\model\MaterialfileModel;
use app\quality\model\ProcedureAttachmentModel;
use app\quality\model\ProcedureModel;
use app\quality\model\ProjectAttachmentModel;
use app\quality\model\StageModel1;
use app\quality\model\StageModel2;
use app\quality\model\StageModel3;
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
        $attachment = new ProjectAttachmentModel();
        $uid = request()->param('uid');
        $pid = request()->param('pid');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/acceptance');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/acceptance/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            $data = [
                'owner' => session('username'),
                'date' => date("Y-m-d H:i:s"),
                'dept' => session('dept'),
                'path' => $path,
                'uid' => $uid,
                'pid' => $pid,
                'filename' => $filename
            ];
            $flag = $attachment->insertAttachment($data);
            return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
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
        $id = request()->param('uid');
        $table_name = request()->param('table_name');
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
                if($table_name == 'jlys'){
                    $stage = new StageModel1();
                }else if($table_name == 'xsys'){
                    $stage = new StageModel2();
                }else{
                    $stage = new StageModel3();
                }
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

    public function upload(){
        $mater = new MaterialfileModel();
        // 前台提交的数据
        $major_key = request()->param('major_key'); // 可选 文件自增编号 新增时 可以不必传 注意 修改的时候一定要传
        $entrustment_number = request()->param('entrustment_number/a'); //  委托编号
        $report_number = request()->param('report_number/a'); //  报告编号
        $report_number = request()->param('approach_detection_time/a'); //  进场检测时间/成型日期
        $using_position = request()->param('using_position/a'); //  使用部位/工程部位/检测部位
        $manufacturer = request()->param('manufacturer/a'); //  生产厂家
        $specifications = request()->param('specifications/a'); //  品种/标号/规格/等级/种类
        $lot_number = request()->param('lot_number/a'); //  批号/炉号/型号/桩号/母材批号
        $number_of_delegates = request()->param('number_of_delegates/a'); //  代表数量/进场数量
        $conclusion = request()->param('conclusion/a'); //  结论
        $broken_date = request()->param('broken_date/a'); //  破型日期
        $bids = request()->param('bids/a'); //  标段
        $altitude = request()->param('altitude/a'); //  高程
        $kind = request()->param('kind/a'); //  种类/样品名称/检测项目
        $design_strength_grade = request()->param('design_strength_grade/a'); //  设计强度等级
        $age = request()->param('age/a'); //  期龄
        $compression_strength = request()->param('compression_strength/a'); //  抗压强度
        $bar_diameter = request()->param('bar_diameter/a'); //  钢筋直径
        $status = request()->param('status/a'); //  状态
        $welding = request()->param('welding/a'); //  焊接或连接方式
        $order_number = request()->param('order_number'); //  序号


        // 系统自动生成的数据
        $years = date('Y'); // 年度
        $owner = session('username'); // 上传人
        $rul_date = date("Y-m-d H:i:s"); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/quality/materialfile');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/quality/materialfile/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            // 构造数据
            $data = [
                'years' => $years,
                'group_id' => $group_id,
            ];

            if(empty($major_key)){
                $flag = $mater->insertMater($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $mater->getOne($major_key);
                if(empty($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data['major_key'] = $major_key;
                $flag = $mater->editMater($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }


    //视频上传V
    public function uploadVideo(){
        if(request()->isAjax()){
            return json(['code' => 1]);
        }
        $video = new ConstructionModel();
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/video');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/video/' . str_replace("\\","/",$temp);
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