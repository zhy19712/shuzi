<?php

namespace app\safety\controller;
use app\admin\controller\Base;
use app\safety\model\EducationModel;
use app\safety\model\EvaluationModel;
use app\safety\model\ImprovementModel;
use app\safety\model\ResponsibilityModel;
use app\safety\model\RevisionrecordModel;
use app\safety\model\RiskSourcesModel;
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
use app\safety\model\AccidentreportModel;
use app\safety\model\WarningsignModel;
use app\safety\model\AccidentinvestigationreportModel;
use app\safety\model\EmergencyplanModel;
use app\safety\model\EmergencyschemeModel;
use app\safety\model\EmergencyimagedataModel;
use app\safety\model\EmergencyrehearsalModel;
use app\safety\model\EmergencydisposalModel;
use app\safety\model\EmergencyreviseModel;
use think\Db;

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
        // 前台提交的数据
        $id = request()->param('id'); // 可选 文件自增编号 新增时 可以不必传，如果传了 就赋值为空 注意 修改的时候一定要传
        $group_id = request()->param('group_id'); // 必须  文件所属分组的编号 也就是当前选择的节点id编号
        $number = request()->param('number'); // 标准号
        $sdi_name = request()->param('sdi_name'); // 文件名称
        $go_date = request()->param('go_date'); // 施行日期
        $standard = request()->param('standard'); // 替代标准
        $evaluation = request()->param('evaluation'); // 适用性评价
        $evaluation = $evaluation == '0' ? '适用' : '过期';
        $sdi_user = request()->param('sdi_user'); // 识别人
        $remark = request()->param('remark'); // 备注

        // 系统自动生成的数据
        $years = date('Y'); // 年度
        $owner = session('username'); // 上传人
        $sdi_date = date("Y-m-d H:i:s"); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/statutesdi');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/statutesdi/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($sdi_name == '等待上传...' || empty($sdi_name)){
                $sdi_name = $filename;
            }
            // 构造数据
            $data = [
                'years' => $years,
                'group_id' => $group_id,
                'number' => $number,
                'sdi_name' => $sdi_name,
                'go_date' => $go_date,
                'standard' => $standard,
                'evaluation' => $evaluation,
                'sdi_user' => $sdi_user,
                'filename' => $filename,
                'owner' => $owner,
                'sdi_date' => $sdi_date,
                'path' => $path,
                'remark' => $remark
            ];
            // 解决前台新增时老是把id赋值为 WU_FILE_ 的问题
            $is_add = explode('_',$id);
            if(empty($id) || $is_add[0] == 'WU')
            {
                $flag = $sdi->insertSdi($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $sdi->getOne($id);
                if(empty($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                // 当 存在替代标准 适用性评价 状态为 : 过期  时 新增一条 修编记录
                if(!empty($standard) && $evaluation == -1){
                    $pname = Db::name('safety_sdi_node')->where('id',$data_older['group_id'])->value('pname');
                    $record = new RevisionrecordModel();
                    $re_data = [
                        'record_name' => $data_older['sdi_name'],
                        'original_number' => $data_older['number'],
                        'replace_number' => $standard,
                        'replace_time' => date("Y-m-d H:i:s"),
                        'owner' => session('username'),
                        'record_type' => '法规标准识别'.$pname
                    ];
                    $re_flag = $record->insertRecord($re_data);
                    if($re_flag['code'] == '-1'){
                        return json($re_flag);
                    }
                }

                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data['id'] = $id;
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
        // 前台提交的数据
        $id = request()->param('id'); // 可选 文件自增编号 新增时 可以不必传，如果传了 就赋值为空 注意 修改的时候一定要传
        $group_id = request()->param('group_id'); // 必须  文件所属分组的编号 也就是当前选择的节点id编号
        $number = request()->param('number'); // 标准号
        $rul_name = request()->param('rul_name'); // 名称
        $go_date = request()->param('go_date'); // 施行日期
        $standard = request()->param('standard'); // 替代标准
        $evaluation = request()->param('evaluation'); // 适用性评价
        $rul_user = request()->param('rul_user'); // 识别人
        $remark = request()->param('remark'); // 备注

        // 系统自动生成的数据
        $years = date('Y'); // 年度
        $owner = session('username'); // 上传人
        $rul_date = date("Y-m-d H:i:s"); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/rules');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/rules/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($rul_name == '等待上传...' || empty($rul_name)){
                $rul_name = $filename;
            }
            // 构造数据
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
                'owner' => $owner,
                'rul_date' => $rul_date,
                'path' => $path,
                'remark' => $remark
            ];
            // 解决前台新增时老是把id赋值为 WU_FILE_ 的问题
            $is_add = explode('_',$id);
            if(empty($id) || $is_add[0] == 'WU')
            {
                $flag = $rules->insertRules($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $rules->getOne($id);
                if(empty($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }

                // 当 存在替代标准 适用性评价 状态为 : 过期  时 新增一条 修编记录
                if(!empty($standard) && $evaluation == '过期'){
                    $pname = Db::name('safety_sdi_node')->where('id',$data_older['group_id'])->value('pname');
                    $record = new RevisionrecordModel();
                    $re_data = [
                        'record_name' => $data_older['rul_name'],
                        'original_number' => $data_older['number'],
                        'replace_number' => $standard,
                        'replace_time' => date("Y-m-d H:i:s"),
                        'owner' => session('username'),
                        'record_type' => '规章制度'.$pname
                    ];
                    $re_flag = $record->insertRecord($re_data);
                    if($re_flag['code'] == '-1'){
                        return json($re_flag);
                    }
                }

                if(file_exists(unlink($data_older['path']))){
                    unlink($data_older['path']);
                }
                $data['id'] = $id;
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
                    'path' => $path
                ];
                $flag = $equipment->insertSpecialEquipmentManagePic($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data = [
                    'id' => $id,
                    'pid' => $pid,
                    'name' => $filename,
                    'picture_name' => $filename,
                    'path' => $path

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

    /*
    *事故报告上传
    * @return \think\response\Json
    */
    public function uploadAccidentreport(){
        /**
         * id 事故报告表id
         * name 文件名
         * filename 上传文件名
         * number 编号
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径

         */
        $accident = new AccidentreportModel();
        $id = request()->param('aid');
        $number = request()->param('number');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/accidentreport');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/accidentreport/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'name' => $filename,
                    'filename' => $filename,
                    'number' => $number,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $accident->insertAccidentreport($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $accident->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'name' => $filename,
                    'filename' => $filename,
                    'number' => $number,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $accident->editAccidentreport($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
    * 警示标志图片上传
    * @return \think\response\Json
    */
    public function uploadWarningsign(){
        /**
         * id 警示标志表自增id
         * selfid 区别警示标志类别id
         * name 上传的警示标志原图片名
         * filename 上传的警示标志图片名
         * standard_number 标准号
         * warn_name 名称
         * structure_size 结构尺寸
         * measurement_unit 计量单位
         * date 上传时间
         * remark 备注
         * path 文件路径
         */


        $warn = new WarningsignModel();
        $id = request()->param('aid');
        $selfid = request()->param('selfid');
        $standard_number = request()->param('standard_number');
        $warn_name = request()->param('warn_name');
        $structure_size = request()->param('structure_size');
        $measurement_unit = request()->param('measurement_unit');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/warningsign');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/warningsign/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'selfid' => $selfid,
                    'standard_number' => $standard_number,
                    'warn_name' => $warn_name,
                    'structure_size' => $structure_size,
                    'measurement_unit' => $measurement_unit,
                    'name' => $filename,
                    'filename' => $filename,
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $warn->insertWarningsign($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $warn->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'selfid' => $selfid,
                    'standard_number' => $standard_number,
                    'warn_name' => $warn_name,
                    'structure_size' => $structure_size,
                    'measurement_unit' => $measurement_unit,
                    'name' => $filename,
                    'filename' => $filename,
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $warn->editWarningsign($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
    * 事故调查报告文件上传
    * @return \think\response\Json
    */
    public function uploadAccidentinvestigationreport(){
        /**
         * id 安全生产文明建设文件上传id
         * number 编号
         * name 事故调查报告上传原文件名
         * filename 事故调查报告文件名
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 文件路径

         */
        $accident = new AccidentinvestigationreportModel();
        $id = request()->param('aid');
        $number = request()->param('number');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/accidentinvestigationreport');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/accidentinvestigationreport/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'number' => $number,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $accident->insertAccidentinvestigationreport($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $accident->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'number' => $number,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $accident->editAccidentinvestigationreport($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 通用上传方法
     */
    public function upload()
    {
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/normal/');
        if($info){
            $path = './uploads/normal/' . str_replace("\\","/",$info->getSaveName());
            return json(['code'=>1,'msg'=>'上传成功','data'=>$path]);
        }else{
            return json(['code'=>-1,'msg'=>'上传失败']);
        }
    }


    /**
     * 绩效评定
     * 一岗双责绩效评定
     * 安全标准化评估报告
     * 安全文明施工年度工作总结
     * 共用 新增 或 (有文件上传的修改)
     * @return \think\response\Json
     * @author hutao
     */
    public function uploadEval(){
        $eval = new EvaluationModel();
        // 前台 页面提交的数据
        $id = request()->param('id'); // 可选 文件自增编号 新增时 可以不必传，如果传了 就赋值为空 注意 修改的时候一定要传
        $type = request()->param('type'); // 必填 type 是1 绩效评定  2评估报告 3工作总结
        $eval_name = request()->param('eval_name'); // 可选 文件名称 用户输入的文件名称 不传 默认和原文件名称一致
        $years = $quarter = '';
        if($type == '1'){ //  一岗双责绩效评定
            $years = request()->param('years'); // 必填 年度
            $quarter = request()->param('quarter'); // 必填 季度
        }else if($type == '3'){ //  安全文明施工年度工作总结
            $years = request()->param('years'); // 必填 年度
        }
        $remark = request()->param('remark'); // 可选 备注

        // 系统自动生成的数据
        $owner = session('username'); // 上传人
        $eval_date = date('Y-m-d H:i:s'); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/eval');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/eval/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($eval_name == '等待上传...' || empty($eval_name)){
                $eval_name = $filename;
            }
            if($type == '1'){ //  一岗双责绩效评定
                $data = [
                    'type' => $type,
                    'eval_name' => $eval_name,
                    'filename' => $filename,
                    'owner' => $owner,
                    'eval_date' => $eval_date,
                    'path' => $path,
                    'years' => $years,
                    'quarter' => $quarter,
                    'remark' => $remark
                ];
            }else if ($type == '3'){ //  安全文明施工年度工作总结
                $data = [
                    'type' => $type,
                    'eval_name' => $eval_name,
                    'filename' => $filename,
                    'owner' => $owner,
                    'eval_date' => $eval_date,
                    'path' => $path,
                    'years' => $years,
                    'remark' => $remark
                ];
            }else{ // 安全标准化评估报告
                $data = [
                    'type' => $type,
                    'eval_name' => $eval_name,
                    'filename' => $filename,
                    'owner' => $owner,
                    'eval_date' => $eval_date,
                    'path' => $path,
                    'remark' => $remark
                ];
            }
            // 解决前台新增时老是把id赋值为 WU_FILE_ 的问题
            $is_add = explode('_',$id);
            if(empty($id) || $is_add[0] == 'WU'){
                $flag = $eval->insertEval($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $eval->getOne($id);
                if(isNull($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data['id'] = $id;
                $flag = $eval->editEval($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /**
     * 持续改进 ----  新增 或 (有文件上传的修改)
     * @return \think\response\Json
     * @author hutao
     */
    public function uploadImprovement(){
        $improve = new ImprovementModel();
        // 前台 页面提交的数据
        $id = request()->param('id'); // 可选 文件自增编号 新增时 可以不必传，如果传了 就赋值为空 注意 修改的时候一定要传
        $ment_name = request()->param('ment_name'); // 可选 文件名称 用户输入的文件名称 不传 默认和原文件名称一致
        $years = request()->param('years'); // 必填 年度
        $remark = request()->param('remark'); // 可选 备注

        // 系统自动生成的数据
        $owner = session('username'); // 上传人
        $ment_date = date('Y-m-d H:i:s'); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/improve');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/improve/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($ment_name == '等待上传...' || empty($ment_name)){
                $ment_name = $filename;
            }
            $data = [
                'ment_name' => $ment_name,
                'filename' => $filename,
                'path' => $path,
                'years' => $years,
                'owner' => $owner,
                'ment_date' => $ment_date,
                'remark' => $remark
            ];
            // 解决前台新增时老是把id赋值为 WU_FILE_ 的问题
            $is_add = explode('_',$id);
            if(empty($id) || $is_add[0] == 'WU'){
                $flag = $improve->insertImprovement($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $improve->getOne($id);
                if(isNull($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data['id'] = $id;
                $flag = $improve->editImprovement($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }


    /**
     * 重大危险源识别与管理 -- 新增 或 (有文件上传的修改)
     * @return \think\response\Json
     * @author hutao
     */
    public function uploadSources(){
        $sources = new RiskSourcesModel();
        // 前台 页面提交的数据
        $id = request()->param('id'); // 可选 文件自增编号 新增时 可以不必传，如果传了 就赋值为空 注意 修改的时候一定要传
        $pid = request()->param('pid'); // 必须 文件归属的父级节点编号 新增时 一定要有   修改时可以不传
        $zid = request()->param('zid'); // 必须 文件归属的子级节点编号 新增时 一定要有   修改时可以不传
        $risk_name = request()->param('risk_name'); // 可选 文件名称 用户输入的文件名称 不传 默认和原文件名称一致
        $number = request()->param('number'); // 可选 文件编号
        $remark = request()->param('remark'); // 可选 备注

        // 系统自动生成的数据
        $owner = session('username'); // 上传人
        $risk_date = date('Y-m-d H:i:s'); // 上传时间

        // 上传的文件
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/sources');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/sources/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($risk_name == '等待上传...' || empty($risk_name)){
                $risk_name = $filename;
            }
            $data = [
                'pid' => $pid,
                'zid' => $zid,
                'risk_name' => $risk_name,
                'number' => $number,
                'owner' => $owner,
                'risk_date' => $risk_date,
                'filename' => $filename,
                'path' => $path,
                'remark' => $remark
            ];
            // 解决前台新增时老是把id赋值为 WU_FILE_ 的问题
            $is_add = explode('_',$id);
            if(empty($id) || $is_add[0] == 'WU'){
                $flag = $sources->insertRiskSources($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $sources->getOne($id);
                if(isNull($data_older)){
                    return json(['code' => '0', 'msg' => '无效的编号']);
                }
                if(file_exists($data_older['path'])){
                    unlink($data_older['path']);
                }
                $data['id'] = $id;
                $flag = $sources->editRiskSources($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }

    /*
     * 应急预案修订文件上传上传
     * @return \think\response\Json
     */
    public function uploadEmergencyplan(){
        /**
         * id 应急预案表中的自增id
         * preplan_file_name 文件名称
         * name 上传原文件名
         * filename 上传文件名
         * preplan_number 文件编号
         * version_number 版本号
         * alternative_version 替代版本
         * applicability 适用性评价
         * preplan_state 状态
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 上传文件路径
         */
        $emergencyplan = new EmergencyplanModel();
        $revise = new EmergencyreviseModel();
        $id = request()->param('aid');//获取上传的18的文件id
        $preplan_number = request()->param('preplan_number');//文件编号
        $version_number = request()->param('version_number');//文件版本号
        $alternative_version = request()->param('alternative_version');//文件替代版本号
        $applicability = request()->param('applicability');//适用性评价
        $preplan_state = request()->param('preplan_state');//状态
        $remark = request()->param('remark');//备注
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/emergencyplan');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/emergencyplan/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($preplan_state == "已上传")
            {
                $data = [
                    'id' => $id,
                    'preplan_number' => $preplan_number,
                    'version_number' => $version_number,
                    'alternative_version' => $alternative_version,
                    'applicability' => $applicability,
                    'preplan_state' => $preplan_state,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                //查询员版本路径
                $emergency_revise = $emergencyplan ->getOne($id);
                $data1 = [
                    'preplan_file_name' => $emergency_revise['preplan_file_name'],
                    'version_number' => $version_number,
                    'alternative_version' => $alternative_version,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'version_number_path' => $emergency_revise['path'],
                    'alternative_version_path' => $path//替换版本路径
                ];
                $flag = $emergencyplan->editEmergencyplan($data);
                $flag1 = $revise->insertEmergencyrevise($data1);
                if($flag['code'] && $flag1['code'])
                {
                    return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
                }
            }



        }else{
            echo $file->getError();
        }
    }

    /*
     * 应急预案编辑文件上传
     * @return \think\response\Json
     */
    public function uploadEmergencyplanedit(){
        /**
         * id 应急预案表中的自增id
         * preplan_file_name 文件名称
         * name 上传原文件名
         * filename 上传文件名
         * preplan_number 文件编号
         * version_number 版本号
         * alternative_version 替代版本
         * applicability 适用性评价
         * preplan_state 状态
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 上传文件路径
         */
        $emergencyplan = new EmergencyplanModel();
        $revise = new EmergencyreviseModel();
        $id = request()->param('aid');//获取上传的18的文件id
        $preplan_number = request()->param('preplan_number');//文件编号
        $version_number = request()->param('version_number');//文件版本号
        $alternative_version = request()->param('alternative_version');//文件替代版本号
        $applicability = request()->param('applicability');//适用性评价
        $preplan_state = request()->param('preplan_state');//状态
        $remark = request()->param('remark');//备注
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/emergencyplan');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/emergencyplan/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if($preplan_state == "已上传")
            {
                $data = [
                    'id' => $id,
                    'preplan_number' => $preplan_number,
                    'version_number' => $version_number,
                    'alternative_version' => $alternative_version,
                    'applicability' => $applicability,
                    'preplan_state' => $preplan_state,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyplan->editEmergencyplan($data);

                    return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);

            }

        }else{
            echo $file->getError();
        }
    }



    /*
     * 应急演练方案文件上传
     * @return \think\response\Json
     */
    public function uploadEmergencyscheme(){
        /**
         * id 应急演练方案自增id
         * name 应急演练上传文件原文件名
         * filename 应急演练上传文件名
         * number 编号
         * date 上传时间
         * owner 上传人
         * remark 备注
         * path 文件路径

         */
        $emergencyscheme = new EmergencyschemeModel();
        $id = request()->param('aid');
        $number = request()->param('number');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/emergencyscheme');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/emergencyscheme/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'number' => $number,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyscheme->insertEmergencyrehearsalscheme($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $emergencyscheme->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'number' => $number,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyscheme->editEmergencyrehearsalscheme($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }


    /*
     * 应急演练影像资料文件上传
     * @return \think\response\Json
     */
    public function uploadEmergencyimagedata(){
        /**
         * id 应急演练影像资料自增id
         * name 应急演练影像资料上传原文件名
         * filename 应急演练影像资料上传文件名
         * place 地点
         * date 上传时间
         * owner 上传人
         * remark 备注
         * path 文件路径

         */
        $emergencyimagedata = new EmergencyimagedataModel();
        $id = request()->param('aid');
        $place = request()->param('place');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/emergencyimagedata');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/emergencyimagedata/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'place' => $place,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyimagedata->insertEmergencyimagedata($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $emergencyimagedata->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'place' => $place,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyimagedata->editEmergencyimagedata($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }


    /*
     * 应急演练文件上传
     * @return \think\response\Json
     */
    public function uploadEmergencyrehearsal(){
        /**
         * id 应急演练自增id
         * name 应急演练上传原文件名
         * filename 应急演练上传文件名
         * number 编号
         * date 上传时间
         * owner 上传人
         * remark 备注
         * path 文件路径

         */
        $emergencyrehearsal = new EmergencyrehearsalModel();
        $id = request()->param('aid');
        $number = request()->param('number');
        $remark = request()->param('remark');
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/$emergencyrehearsal');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/$emergencyrehearsal/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($id))
            {
                $data = [
                    'number' => $number,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyrehearsal->insertEmergencyrehearsal($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $emergencyrehearsal->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'number' => $number,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencyrehearsal->editEmergencyrehearsal($data);
                return json(['code' => $flag['code'], 'msg' => $flag['msg']]);
            }
        }else{
            echo $file->getError();
        }
    }


    /*
     * 应急处置文件上传
     * @return \think\response\Json
     */
    public function uploadEmergencydisposal(){
        /**
         * id 应急处置表自增id
         * preplan_file_name 文件名称
         * name 上传原文件名
         * filename 上传文件名
         * preplan_number 文件编号
         * version_number 版本号
         * alternative_version 替代版本
         * applicability 适用性评价
         * preplan_state 状态
         * owner 上传人
         * date 上传时间
         * remark 备注
         * path 上传文件路径
         */
        $emergencydisposal = new EmergencydisposalModel();
        $id = request()->param('aid');//获取上传的18的文件id
        $panid = request()->param('panid');//判断编辑时候是否有文件上传
        $preplan_number = request()->param('preplan_number');//文件编号
        $applicability = request()->param('applicability');//适用性评价
        $remark = request()->param('remark');//备注
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/safety/emergencydisposal');
        if($info){
            $temp = $info->getSaveName();
            $path = './uploads/safety/emergencydisposal/' . str_replace("\\","/",$temp);
            $filename = $file->getInfo('name');
            if(empty($panid))//无文件上传
            {
                $data = [
                    'id' => $id,
                    'preplan_number' => $preplan_number,
                    'applicability' => $applicability,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencydisposal->editEmergencydisposal($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }else{
                $data_older = $emergencydisposal->getOne($id);
                unlink($data_older['path']);
                $data = [
                    'id' => $id,
                    'preplan_number' => $preplan_number,
                    'applicability' => $applicability,
                    'name' => $filename,
                    'filename' => $filename,
                    'owner' => session('username'),
                    'date' => date("Y-m-d H:i:s"),
                    'path' => $path,
                    'remark' => $remark
                ];
                $flag = $emergencydisposal->editEmergencydisposal($data);
                return json(['code' => $flag['code'],  'msg' => $flag['msg']]);
            }



        }else{
            echo $file->getError();
        }
    }

}