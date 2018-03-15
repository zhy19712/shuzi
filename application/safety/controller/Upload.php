<?php

namespace app\safety\controller;
use app\admin\controller\Base;
use app\safety\model\EducationModel;
use app\safety\model\ResponsibilityModel;
use app\safety\model\RulesregulationsModel;
use app\safety\model\SafetyGoalAnualModel;
use app\safety\model\SafetyGoalGeneralModel;
use app\safety\model\ResponsibilityinstyGroupModel;
use app\safety\model\SafetyResponsibilitycultureModel;
use app\safety\model\SafetyResponsibilityinfoModel;
use app\safety\model\StatutestdiModel;
use app\safety\model\FullparticipationModel;
use app\safety\model\EquipmentCheckAcceptModel;
use app\safety\model\SafetySpecialEquipmentManagementModel;
use app\safety\model\JobhealthGroupModel;

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
        $years = date('Y');
        $group_id = request()->param('group_id');
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
            if($sdi_name == '等待上传...'){
                $houzhui = substr(strrchr($filename, '.'), 1);
                $sdi_name = basename($filename,".".$houzhui); // 取不带后缀的文件名
            }
            if(empty($id))
            {
                $data = [
                    'years' => $years,
                    'group_id' => $group_id,
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
                    'years' => $years,
                    'group_id' => $group_id,
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
        $years = date('Y');
        $group_id = request()->param('group_id');
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
                    'years' => $years,
                    'group_id' => $group_id,
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
                $flag = $rules->insertRules($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $rules->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'years' => $years,
                    'group_id' => $group_id,
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
                $flag = $rules->editRules($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
     * 设置机构文件上传
     * @return \think\response\Json
    */
    public function uploadResponsibilityinstyGroup(){
        /**
         * id 设置机构文件上传id
         * selfid 标记的节点id
         * name 文件名
         * filename 上传文件名
         * owner 上传人
         * date 上传时间
         * version 版本
         * remark 备注
         * path 文件路径

         */
        $group = new ResponsibilityinstyGroupModel();
        $id = request()->param('aid');
        $selfid = request()->param('selfid');
        $remark = request()->param('remark');
        $version = request()->param('version');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/group');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/group/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'selfid' => $selfid,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'version'=>$version,
                    'remark' => $remark,
                    'path' => $path
                ];
                $flag = $group->insertResponsibilityinstyGroup($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $group->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'selfid' => $selfid,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'version'=>$version,
                    'remark' => $remark,
                    'path' => $path
                ];
                $flag = $group->editResponsibilityinstyGroup($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
     * 安全生产文明建设文件上传
     * @return \think\response\Json
    */
    public function uploadSafetyResponsibilityculture(){
        /**
         * id 安全生产文明建设文件上传id
         * name 文件名
         * filename 上传文件名
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径

         */
        $culture = new SafetyResponsibilitycultureModel();
        $id = request()->param('aid');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/culture');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/culture/' . str_replace("\\","/",$temp);
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
                $flag = $culture->insertSafetyResponsibilityculture($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $culture->getOne($id);
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
                $flag = $culture->editSafetyResponsibilityculture($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }
    /*
     *安全生产信息化建设文件上传
     * @return \think\response\Json
    */
    public function uploadSafetyResponsibilityinfo(){
        /**
         * id 安全生产信息化建设文件上传id
         * name 文件名
         * filename 上传文件名
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径

         */
        $responsibilityinfo = new SafetyResponsibilityinfoModel();
        $id = request()->param('aid');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/responsibilityinfo');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/responsibilityinfo/' . str_replace("\\","/",$temp);
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
                $flag = $responsibilityinfo->insertSafetyResponsibilityinfo($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $responsibilityinfo->getOne($id);
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
                $flag = $responsibilityinfo->editSafetyResponsibilityinfo($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }
    /*
     *安全生产责任制文件上传
     * @return \think\response\Json
    */



    public function uploadFullparticipation(){
        /**
         * id 安全生产责任制文件上传id
         * name 文件名
         * filename 上传文件名
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径
         * version 版本

         */
        $fullpart = new FullparticipationModel();
        $id = request()->param('aid');
        $version = request()->param('version');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/fullparticipation');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/fullparticipation/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'version' => $version,
                    'remark' => $remark
                ];
                $flag = $fullpart->insertFullparticipation($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $fullpart->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'version' => $version,
                    'remark' => $remark
                ];
                $flag = $fullpart->editFullparticipation($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 专题教育培训 文件上传
     * @author hutao
     */
    public function uploadEdu(){
        /**
         * group_id   所属分类编号
        edu_time 培训时间
        lecturer 讲课人
        address 培训地址
        trainee 培训人员
        num 培训人数
        remark 备注
        content 培训内容
        material_name 培训材料手动输入名
        ma_filename 培训材料原文件名
        ma_path 培训材料文件路径
        record_name 培训记录手动输入名
        re_filename 培训记录原文件名
        re_path 培训记录文件路径
        owner 上传人
        edu_date 上传时间
         */
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/education');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }

    /*
     *设备设施管理文件上传
     * @return \think\response\Json
    */
    public function uploadEquipmentCheckAccept(){
        /**
         * id 安全生产责任制文件上传id
         * selfid 标记的节点id
         * name 文件名
         * filename 上传文件名
         * checktime 验收时间
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径
         * version 版本

         */
        $equipment = new EquipmentCheckAcceptModel();
        $id = request()->param('aid');
        $selfid = request()->param('selfid');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $checktime = request()->param('checktime');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/equipmentCheckAccept');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/equipmentCheckAccept/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'selfid' => $selfid,
                    'name' => $filename,
                    'filename' => $filename,
                    'checktime' => $checktime,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $equipment->insertEquipmentCheckAccept($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $equipment->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'selfid' => $selfid,
                    'name' => $filename,
                    'filename' => $filename,
                    'checktime' => $checktime,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $equipment->editEquipmentCheckAccept($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
     *特种设备管理文件图片上传
     * @return \think\response\Json
    */
    public function uploadSpecialequipmentmanagement(){
        /**
         * id 安全生产责任制文件上传id
         * selfid 标记的节点id
         * equip_name 设备名称
         * model 型号
         * equip_num 设备编号
         * manufactur_unit 制造单位
         * date_production 出厂日期
         * current_state 当前状态
         * equip_manage_department 设备管理部门
         * safety_machinery_time 安全准用证挂牌时间
         * safety_inspection_num 安全检验合格证书编号
         * inspection_unit 检验单位
         * safety_inspecte_certificate_time 安全检验合格证书有效截止日期
         * date_overhaul 大修日期
         * entry_time 进场时间
         * equip_state 设备状态
         * remark 备注
         * input_time excel表格导入时间
         * name 文件名
         * filename 上传文件名
         * owner 上传人
         * create_time 新增/上传时间
         * path 文件路径
         */
        $equipment = new SafetySpecialEquipmentManagementModel();

        $id = request()->param('cid');//获取特种设备文件的id

        $pid = request()->param('pid');//获取特种设备文件上传图片的uid

        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/specialequipmentmanagement');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/specialequipmentmanagement/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($pid))
            {
                $data = [
                    'uid' => $id,
                    'name' => $filename,
                    'picture_name' => $filename,
                    'path' => $path,
                ];
                $flag = $equipment->insertSpecialEquipmentManagePic($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $id,
                    'pid' => $pid,
                    'name' => $filename,
                    'picture_name' => $filename,
                    'path' => $path,

                ];
                $flag = $equipment->editSpecialEquipmentManagementPic($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
     *职业健康检查文件上传
    * @return \think\response\Json
    */

    public function uploadJobhealth(){
        /**
         * id 相关方职业健康检查表id
         * selfid 区别职业健康管理类别的id
         * name 上传文件名
         * filename 上传文件名
         * filenumber 文件编号
         * checktime 检查时间
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径

         */
        $jobhealth = new JobhealthGroupModel();
        $id = request()->param('aid');
        $selfid = request()->param('selfid');
        $filenumber = request()->param('filenumber');
        $checktime = request()->param('checktime');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/jobhealth');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/jobhealth/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'selfid' => $selfid,
                    'name' => $filename,
                    'filename' => $filename,
                    'filenumber' => $filenumber,
                    'checktime' => $checktime,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $jobhealth->insertJobhealth($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $jobhealth->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'selfid' => $selfid,
                    'name' => $filename,
                    'filename' => $filename,
                    'filenumber' => $filenumber,
                    'checktime' => $checktime,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $jobhealth->editJobhealth($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 监理部职业健康管理 文件上传
     * @author hutao
     */
    /**
     * @author
     */
    public function uploadHealthmanage(){
        /**
        id 监理部职业健康管理表id
        fullname 姓名
        age 年龄
        station 岗位
        inform_name 职业危害告知书原文件名
        inform_filename 职业危害告知书上传文件名
        inform_path 职业危害告知书上传文件路径
        physical_name 职业健康体检报告原文件名
        physical_filename 职业健康体检报告上传文件名
        physical_path 职业健康体检报告文件路径
        owner 上传人
        date 上传时间
         */
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/healthmanage');
        if($info){
            echo $info->getSaveName();
        }else{
            echo $file->getError();
        }
    }

}