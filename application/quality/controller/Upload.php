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
use app\quality\model\SurveyDataModel;
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

    /**
     * 试验资料 有文件上传的 新增或者修改
     * @return \think\response\Json
     */
    public function uploadMater(){
        $mater = new MaterialfileModel();
        // 前台提交的数据
        $major_key = request()->param('major_key'); // 可选 文件自增编号 新增时 可以不必传 注意 修改的时候一定要传
        // 数组
        $entrustment_number_1 = request()->param('entrustment_number'); //  委托编号
        $entrustment_number = explode(',',$entrustment_number_1);
        $report_number_1 = request()->param('report_number'); //  报告编号
        $report_number = explode(',',$report_number_1);
        $approach_detection_time_1 = request()->param('approach_detection_time'); //  进场检测时间/成型日期
        $approach_detection_time = explode(',',$approach_detection_time_1);
        $using_position_1 = request()->param('using_position'); //  使用部位/工程部位/检测部位
        $using_position = explode(',',$using_position_1);
        $manufacturer_1 = request()->param('manufacturer'); //  生产厂家
        $manufacturer = explode(',',$manufacturer_1);
        $specifications_1 = request()->param('specifications'); //  品种/标号/规格/等级/种类
        $specifications = explode(',',$specifications_1);
        $lot_number_1 = request()->param('lot_number'); //  批号/炉号/型号/桩号/母材批号
        $lot_number = explode(',',$lot_number_1);
        $number_of_delegates_1 = request()->param('number_of_delegates'); // 代表数量/进场数量
        $number_of_delegates = explode(',',$number_of_delegates_1);
        $conclusion_1 = request()->param('conclusion'); // 结论
        $conclusion = explode(',',$conclusion_1);

        $kind_1 = request()->param('kind'); // 样品名称/检测项目
        $kind = explode(',',$kind_1);
        $bids_1 = request()->param('bids'); //  标段
        $bids = explode(',',$bids_1);

        // 单个值
        $broken_date = request()->param('broken_date'); // 破型日期
        $altitude = request()->param('altitude'); //  高程
        $design_strength_grade = request()->param('design_strength_grade'); //  设计强度等级
        $age = request()->param('age'); //  期龄
        $compression_strength = request()->param('compression_strength'); //  抗压强度
        $bar_diameter = request()->param('bar_diameter'); //  钢筋直径
        $status = request()->param('status'); //  状态
        $welding = request()->param('welding'); //  焊接或连接方式
        $order_number = request()->param('order_number'); //  序号

        $data = [];
        $en = $ma = $sp = $lot = $del = 0;
        for($i = 0; $i < 20;$i++){
            // 委托编号、报告编号、进场检测时间
            if($i != 16){
                $data[$i]['entrustment_number'] = $entrustment_number[$en];
                $data[$i]['report_number'] = $report_number[$en];
                $en++;
            }
            $data[$i]['approach_detection_time'] = $approach_detection_time[$i];
            $data[$i]['using_position'] = $using_position[$i]; // 使用部位
            if($i < 15){
                $data[$i]['manufacturer'] = $manufacturer[$ma]; // 生产厂家
                $ma++;
            }
            if(!in_array($i,[2,14,17,18,19])){
                $data[$i]['specifications'] = $specifications[$sp]; // 品种/标号/规格/等级/种类
                $sp++;
            }
            if(!in_array($i,[3,4,16,18,19])){
                $data[$i]['lot_number'] = $lot_number[$lot]; // 批号/炉号/型号/桩号/母材批号
                $lot++;
            }
            if(!in_array($i,[15,17,18,19])){
                $data[$i]['number_of_delegates'] = $number_of_delegates[$del]; // 代表数量/进场数量
                $del++;
            }
            $data[$i]['conclusion'] = $conclusion[$i]; // 结论
        }

        //  混凝土 ==>委托编号、报告编号、成型日期、破型日期、标段、工程部位、桩号、高程、种类、设计强度等级、龄期(d)、抗压强度(Mpa)、结论
        $data[15]['broken_date'] = $broken_date;
        $data[15]['bids'] = $bids[0];
        $data[15]['altitude'] = $altitude;
        $data[15]['design_strength_grade'] = $design_strength_grade;
        $data[15]['age'] = $age;
        $data[15]['compression_strength'] = $compression_strength;
        // 钢筋接头==> 检测日期、标段、工程部位、品种规格、钢筋直径、代表数量(个)、结论
        $data[16]['bids'] = $bids[1];
        $data[16]['bar_diameter'] = $bar_diameter;
        // 止水材料接头==> 委托编号、报告编号、检测日期、标段、工程部位、母材批号、状态、焊接或连接方式、结论
        $data[17]['bids'] = $bids[2];
        $data[17]['status'] = $status;
        $data[17]['welding'] = $welding;
        // 压实度==> 委托编号、报告编号、检测日期、标段、检测部位、样品名称、结论
        $data[18]['bids'] = $bids[3];
        $data[18]['kind'] = $kind[0];
        // 地基承载力==> 序号、委托编号、报告编号、检测日期、标段、检测部位、检测项目、结论
        $data[19]['order_number'] = $order_number;
        $data[19]['bids'] = $bids[4];
        $data[19]['kind'] = $kind[1];


        // 系统自动生成的数据
        $owner = session('username'); // 上传人
        $date = date("Y-m-d H:i:s"); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/quality/materialfile');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/quality/materialfile/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            // 构造数据
            $data[0]['owner'] = $owner;
            $data[0]['date'] = $date;
            $data[0]['filename'] = $filename;
            $data[0]['path'] = $path;

            if(empty($major_key)){
                halt('insert');
                $flag = $mater->insertMater($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                halt('edit');
                $data_older = $mater->getOne($major_key);
                if(empty($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data[0]['id'] = $major_key;
                $flag = $mater->editMater($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 测量资料 有文件上传的 新增或者修改
     * @return \think\response\Json
     */
    public function uploadSurvey(){
        $survey = new SurveyDataModel();
        // 前台提交的数据
        $major_key = request()->param('major_key'); // 可选 文件自增编号 新增时 可以不必传 注意 修改的时候一定要传
        $k_check_single_number = request()->param('k_check_single_number'); // 开挖报验单号
        $k_reception_time = request()->param('k_reception_time'); // 开挖验收时间
        $h_check_single_number = request()->param('h_check_single_number'); // 混凝土报验单号
        $h_reception_time = request()->param('h_reception_time'); // 混凝土验收时间

        // 系统自动生成的数据
        $owner = session('username'); // 上传人
        $date = date("Y-m-d H:i:s"); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/quality/survey');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/quality/survey/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            // 构造数据
            $data = [
                'owner' => $owner,
                'date' => $date,
                'path' => $path,
                'filename' => $filename,
                'k_check_single_number' => $k_check_single_number,
                'k_reception_time' => $k_reception_time,
                'h_check_single_number' => $h_check_single_number,
                'h_reception_time' => $h_reception_time
            ];

            if(empty($major_key)){
                $flag = $survey->insertSurvey($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $survey->getOne($major_key);
                if(empty($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data['id'] = $major_key;
                $flag = $survey->editSurvey($data);
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