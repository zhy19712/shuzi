<?php

namespace app\admin\controller;
use app\admin\model\Node;
use think\Config;
use think\Loader;
use think\Db;
use think\Controller;
use app\quality\model\AnchorPointModel;
use app\quality\model\CustomAttributeModel;
use app\quality\model\LabelSnapshotModel;
use app\quality\model\PictureModel;
use think\Session;

class Index extends Base
{
    public function index()
    {
        acceptanceWarning();//启动时刷新验收预警
        return $this->fetch('/index');
    }


    /**
     * [indexPage 后台首页]
     * @return [type] [description]
     */
    public function indexPage()
    {
//         //今日新增会员
//        $today = strtotime(date('Y-m-d 00:00:00'));//今天开始日期
//        $map['create_time'] = array('egt', $today);
//        $member = Db::name('member')->where($map)->count();
//        $this->assign('member', $member);

        $info = array(
            'web_server' => $_SERVER['SERVER_SOFTWARE'],
            'onload'     => ini_get('upload_max_filesize'),
            'think_v'    => THINK_VERSION,
            'phpversion' => phpversion(),
        );

        $this->assign('info',$info);
        return $this->fetch('index');
    }



    /**
     * [userEdit 修改密码]
     * @return [type] [description]
     */
    public function editpwd(){

        if(request()->isAjax()){
            $param = input('post.');
            $user=Db::name('admin')->where('id='.session('uid'))->find();
            if(md5(md5($param['old_password']) . config('auth_key'))!=$user['password']){
               return json(['code' => -1, 'url' => '', 'msg' => '旧密码错误']);
            }else{
                $pwd['password']=md5(md5($param['password']) . config('auth_key'));
                Db::name('admin')->where('id='.$user['id'])->update($pwd);
                session(null);
                cache('db_config_data',null);//清除缓存中网站配置信息
                return json(['code' => 1, 'url' => 'index/index', 'msg' => '密码修改成功']);
            }
        }
        return $this->fetch();
    }


    /**
     * 清除缓存
     */
    public function clear() {
        if (delete_dir_file(CACHE_PATH) && delete_dir_file(TEMP_PATH)) {
            return json(['code' => 1, 'msg' => '清除缓存成功']);
        } else {
            return json(['code' => 0, 'msg' => '清除缓存失败']);
        }
    }

    public function test(){
        $node = new Node();
        $node->getMenu(session('rule'));
        $aa = 1234;
        $result = Db::name('auth_rule')->order('sort')->select();
        $menu = prepareMenu($result);
        return json(['data' => $menu]);

    }




    /************************************3D模型*****************************************************************/

    public function getindex()
    {

        return $this->fetch();
    }

    public function selectperson()
    {
        return $this->fetch();
    }

    /**
     * 管理属性 -- 添加 自定义属性
     * @return string|\think\response\Json
     * @author hutao
     */
    public function addAttr()
    {
        // 新增 前台需要传递 的是  模型图编号 picture_id 属性名 attrKey  属性值 attrVal
        // 编辑 前台需要传递 的是  模型图编号 picture_id 属性名 attrKey  属性值 attrVal   和 这条属性的主键 attrId
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数'],
                ['attrKey', 'require', '属性名不能为空'],
                ['attrVal', 'require', '属性值不能为空']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $data = [];
            $data['picture_number'] = $param['picture_id'];
            $data['attr_name'] = $param['attrKey'];
            $data['attr_value'] = $param['attrVal'];
            $custom = new CustomAttributeModel();
            $id = isset($param['attrId']) ? $param['attrId'] : 0;
            if(empty($id)){
                $flag = $custom->insertTb($data);
            }else{
                if(!is_int($id)){
                    return json(['code' => -1, 'msg' => '属性的编号只能是数字']);
                }
                $data['id'] = $id;
                $flag = $custom->editTb($data);
            }
            return json($flag);
        }
    }

    /**
     * 删除属性
     * @return \think\response\Json
     * @author hutao
     */
    public function delAttr()
    {
        // 前台只需要给我传递 要删除的 属性的主键 attrId
        $param = input('param.');
        // 验证规则
        $rule = [
            ['attrId', 'require|number|gt:-1', '请选择要删除的属性|属性编号只能是数字|属性编号不能为负数']
        ];
        $validate = new \think\Validate($rule);
        //验证部分数据合法性
        if (!$validate->check($param)) {
            return json(['code' => -1,'msg' => $validate->getError()]);
        }
        $node = new CustomAttributeModel();
        $flag = $node->deleteTb($param['attrId']);
        return json($flag);
    }

    /**
     * 回显自定义属性
     * @return \think\response\Json
     * @author hutao
     */
    public function getAttr()
    {
        // 前台需要传递 的是  模型图编号 picture_id
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $custom = new CustomAttributeModel();
            $flag = $custom->getAttrTb($param['picture_id']);
            return json($flag);
        }
    }

    /**
     * 管理属性 -- 添加 描述
     * @return string|\think\response\Json
     * @author hutao
     */
    public function addRemark()
    {
        // 前台需要传递 的是  模型图编号 picture_id 描述 remark
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数'],
                ['remark', 'require', '描述不能为空']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            // 1工程划分模型 2 建筑模型 3三D模型
            $data['id'] = Db::name('quality_model_picture')->where(['picture_type'=>1,'picture_number'=>$param['picture_id']])->value('id');
            if(empty($data['id'])){
                return json(['code'=>1,'msg'=>'不存在的模型编号']);
            }
            $data['remark'] = $param['remark'];
            $pic = new PictureModel();
            $pic->editTb($data);
            return json(['code'=>1,'msg'=>'操作成功']);
        }
    }

    /**
     * 获取模型图描述
     * @return \think\response\Json
     * @author hutao
     */
    public function getRemark()
    {
        // 前台需要传递 的是  模型图编号 picture_id
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $pic = new PictureModel();
            // 1工程划分模型 2 建筑模型 3三D模型
            $id = Db::name('quality_model_picture')->where(['picture_type'=>1,'picture_number'=>$param['picture_id']])->value('id');
            if(empty($id)){
                return json(['code'=>1,'msg'=>'不存在的模型编号']);
            }
            $remark = $pic->getRemarkTb($id);
            return json(['code'=>1,'remark'=>$remark,'msg'=>'模型图描述']);
        }
    }

    /**
     * 添加标注或者快照
     * @return \think\response\Json
     * @author hutao
     */
    public function labelSnapshot()
    {
        // 前台需要传递 的是  模型图编号 picture_id 类型 type 1标注2快照  图片的base64值 label_snapshot
        // 注意：：：如果是标注 那么 就还要 传递 user_name 创建人 create_time 创建时间 remark 备注
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数'],
                ['type', 'require|number|between:1,2', '类型值不能为空|类型值只能是数字|类型值是1或者2'],
                ['label_snapshot', 'require', '图片压缩值不能为空'],
                ['compress_base64', 'require', '图片值不能为空']
            ];
            // 类型 type 1标注2快照
            if(!empty($param['type']) && $param['type'] == 1){
                array_push($rule,['user_name', 'require', '创建人不能为空']);
                array_push($rule,['create_time', 'require|dateFormat:Y-m-d H:i:s', '创建时间不能为空|时间格式有误']);
                array_push($rule,['remark', 'require', '备注不能为空']);
            }
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $data['type'] = $param['type'];
            $data['picture_number'] = $param['picture_id'];
            $data['label_snapshot'] = $param['label_snapshot'];
            $data['base64_val'] = $param['compress_base64'];

            $file = '.'. DS . 'uploads' . DS . 'quality' . DS . 'snapshot' . DS;//文件路径

            if(!file_exists($file))
            {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($file, 0700);
            }

            $new_file = $file.md5(rand(1,9999)).".jpg";

            file_put_contents($new_file, base64_decode($param['compress_base64']));

            $data['base64_val_url'] = $new_file;

//            $user_id = Session::get('uid');
//            $data['user_name'] = Db::name('admin')->where('id',$user_id)->value('name');
            $data['user_name'] = session('username');
            // 类型 type 1标注2快照
            if($data['type'] == 1){
                $data['create_time'] = strtotime($param['create_time']);
                $data['remark'] = $param['remark'];
            }
            $pic = new LabelSnapshotModel();
            $flag = $pic->insertTb($data);
            return json(["flag"=>$flag,"base64_val_url"=>$new_file]);
        }
    }

    /**
     * 删除标注或者快照
     * @return \think\response\Json
     * @author hutao
     */
    public function delLabelSnapshot()
    {
        // 前台需要传递 的是  标注快照的主键 label_snapshot_id
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['label_snapshot_id', 'require|number|gt:-1', '请选择要删除的标注或者快照|标注快照的编号只能是数字|标注快照的编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $id = $param['label_snapshot_id'];
            $pic = new LabelSnapshotModel();

            $info = $pic->getOne($id);
            if($info["base64_val_url"])
            {
                $path = $info["base64_val_url"];

                if(file_exists($path)){
                    unlink($path); //删除文件图片
                }
            }
            $flag = $pic->deleteTb($id);
            return json($flag);
        }
    }

    /**
     * 回显标注或者快照
     * @return \think\response\Json
     * @author hutao
     */
    public function getLabelSnapshot()
    {
        // 前台需要传递 的是  模型图主键 picture_id 类型 type 1标注2快照
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数'],
                ['type', 'require|number|between:1,2', '类型值不能为空|类型值只能是数字|类型值是1或者2']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $pic = new LabelSnapshotModel();

            $flag = $pic->getLabelSnapshotTb($param['type'],$param['picture_id']);

            return json(['code'=>1,"data"=>$flag,"msg"=>"图片的base64值"]);
        }
    }

    /**
     * 创建人 和 创建日期 和 关联构件名称
     * @return \think\response\Json
     * @author huao
     */
    public function labelAttr()
    {
        // 前台什么也不用传递
        // 当前台传递 模型图主键 picture_id 类型 type 等于 3 时 是锚点的请求 后台 返回 创建人 创建日期 关联构件名称
        if($this->request->isAjax()){
            $param = input('param.');
//            $user_id = Session::get('uid');
//            $data['user_name'] = Db::name('admin')->where('id',$user_id)->value('name');
            $data['user_name'] =session('username');
            $data['create_time'] = date('Y-m-d H:i:s');
            if(empty($param['type'])){
                return json(['code'=>1,'data'=>$data,'msg'=>'创建人和创建时间']);
            }else{
                // 验证规则
                $rule = [
                    ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数'],
                    ['type', 'require|number', '类型值不能为空|类型值只能是数字']
                ];
                $validate = new \think\Validate($rule);
                //验证部分数据合法性
                if (!$validate->check($param)) {
                    return json(['code' => -1,'msg' => $validate->getError()]);
                }
                // 1工程划分模型 2 建筑模型 3三D模型
                $data['componentName'] = Db::name('quality_model_picture')->where(['picture_type'=>1,'picture_number'=>$param['picture_id']])->value('picture_name');
                return json(['code'=>1,'data'=>$data,'msg'=>'创建人,创建时间和关联构件名称']);
            }
        }
    }

    /**
     * 添加 或者 编辑 锚点
     * @return \think\response\Json
     * @author hutao
     */
    public function anchorPoint()
    {
        // 前台需要传递 的是  模型图编号 picture_id 锚点名称 anchorName 创建人 user_name 创建日期 create_time 关联构件名称 componentName 备注信息 remark 位置 fObjSelX fObjSelY fObjSelZ
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['picture_id', 'require|number|gt:-1', '请选择模型图|模型图编号只能是数字|模型图编号不能为负数'],
                ['anchorName', 'require', '锚点名称不能为空'],
                ['user_name', 'require', '创建人不能为空'],
                ['create_time', 'require|dateFormat:Y-m-d H:i:s', '创建时间不能为空|时间格式有误'],
                ['componentName', 'require', '关联构件名称不能为空'],
                ['fObjSelX', 'require', 'X坐标不能为空'],
                ['fObjSelY', 'require', 'Y坐标不能为空'],
                ['fObjSelZ', 'require', 'Z坐标不能为空']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            // 1工程划分模型 2 建筑模型 3三D模型
            $data['picture_number'] = $param['picture_id'];
            $data['anchor_name'] = $param['anchorName'];
            $data['user_name'] = $param['user_name'];
            $data['create_time'] = strtotime($param['create_time']);
            $data['component_name'] = $param['componentName'];
            $data['coordinate_x'] = $param['fObjSelX'];
            $data['coordinate_y'] = $param['fObjSelY'];
            $data['coordinate_z'] = $param['fObjSelZ'];
            $data['remark'] = $param['remark'];
            $anchor = new AnchorPointModel();
            // 判断是新增 还是 编辑
            $is_add = $anchor->getAnchorByName($data['anchor_name']);
            if(empty($is_add)){
                $flag = $anchor->insertTb($data);
            }else{
                $data['id'] = $is_add;
                $flag = $anchor->editTb($data,2);
            }
            return json($flag);
        }
    }

    /**
     * 删除锚点
     * @return \think\response\Json
     * @author hutao
     */
    public function delAnchorPoint()
    {
        // 前台需要传递 的是 锚点的主键 anchor_point_id
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['anchor_point_id', 'require|number|gt:-1', '请选择要删除的标注或者快照|标注快照的编号只能是数字|标注快照的编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $id = $param['anchor_point_id'];
            $pic = new AnchorPointModel();
            $flag = $pic->deleteTb($id);
            return json($flag);
        }
    }

    /**
     * 上传附件
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author hutao
     */
    public function uploadAnchorPoint()
    {
        // 前台需要 传递 锚点的主键 anchor_point_id 上传的文件 file
        // 执行上传文件 获取文件编号  attachment_id
        if($this->request->file('file')){
            $file = $this->request->file('file');
        }else{
            return json(['code'=>0,'msg'=>'没有上传文件']);
        }
        $web_config = Db::name('webconfig')->where('web','web')->find();
        $info = $file->validate(['size'=>$web_config['file_size']*1024,'ext'=>$web_config['file_type']])->rule('date')->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'quality' . DS . 'anchor_point');
        if($info) {
            //写入到附件表
            $data = [];
            $data['module'] = 'quality';
            $data['name'] = $info->getInfo('name');//文件名
            $data['filename'] = $info->getFilename();//文件名
            $data['filepath'] = DS . 'uploads' . DS . 'quality' . DS . 'anchor_point' . DS . $info->getSaveName();//文件路径
            $data['fileext'] = $info->getExtension();//文件后缀
            $data['filesize'] = $info->getSize();//文件大小
            $data['create_time'] = time();//时间
            $data['uploadip'] = $this->request->ip();//IP
            $data['user_id'] = Session::has('uid') ? Session::get('uid') : 0;
            if($data['module'] == 'admin') {
                //通过后台上传的文件直接审核通过
                $data['status'] = 1;
                $data['admin_id'] = $data['user_id'];
                $data['audit_time'] = time();
            }
            $data['use'] = $this->request->has('use') ? $this->request->param('use') : 'anchor_point';//用处
            $res['id'] = Db::name('attachment')->insertGetId($data);
            $res['src'] = DS . 'uploads' . DS . 'quality' . DS . 'anchor_point' . DS . $info->getSaveName();
            $res['code'] = 2;
//            addlog($res['id']);//记录日志

            // 保存上传文件记录
            $param = input('param.');
            // 验证规则
            $rule = [
                ['anchor_point_id', 'require|number|gt:-1', '请选择要上传的锚点|锚点的编号只能是数字|锚点的编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $data['id'] = $param['anchor_point_id'];
            $unit = new AnchorPointModel();
            $att_id = $unit->getAttachmentId($data['id']);
            $data['attachment_id'] = $res['id'] . ',' . $att_id;
            $nodeStr = $unit->editTb($data);
            return json($nodeStr);
        } else {
            // 上传失败获取错误信息
            return $this->error('上传失败：'.$file->getError());
        }
    }

    /**
     * 删除附件
     * @return \think\response\Json
     * @author hutao
     */
    public function delAttachment()
    {
        // 前台需要 传递 锚点的主键 anchor_point_id  附件的主键 attachment_id
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['anchor_point_id', 'require|number|gt:-1', '请选择附件所属的锚点|锚点的编号只能是数字|锚点的编号不能为负数'],
                ['attachment_id', 'require|number|gt:-1', '请选择要删除的附件|附件的编号只能是数字|附件的编号不能为负数']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $unit = new AnchorPointModel();
            $unit->delAnchorPointAttachment($param['anchor_point_id'],$param['attachment_id']);
            return json(['code'=>1,'msg'=>'删除成功']);
        }
    }

    /**
     * 下载附件
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author hutao
     */
    public function relationDownload()
    {
        // 前台需要 传递 附件的主键 attachment_id
        $param = input('param.');
        // 验证规则
        $rule = [
            ['attachment_id', 'require|number|gt:-1', '请选择要删除的附件|附件的编号只能是数字|附件的编号不能为负数']
        ];
        $validate = new \think\Validate($rule);
        //验证部分数据合法性
        if (!$validate->check($param)) {
            return json(['code' => -1,'msg' => $validate->getError()]);
        }
        $file_obj = Db::name('attachment')->where('id',$param['attachment_id'])->field('filename,filepath')->find();
        $filePath = '';
        if(!empty($file_obj['filepath'])){
            $filePath = '.' . $file_obj['filepath'];
        }
        if(!file_exists($filePath)){
            return json(['code' => '-1','msg' => '文件不存在']);
        }else if(request()->isAjax()){
            return json(['code' => 1]); // 文件存在，告诉前台可以执行下载
        }else{
            $fileName = $file_obj['filename'];
            $file = fopen($filePath, "r"); //   打开文件
            //输入文件标签
            $fileName = iconv("utf-8","gb2312",$fileName);
            Header("Content-type:application/octet-stream ");
            Header("Accept-Ranges:bytes ");
            Header("Accept-Length:   " . filesize($filePath));
            Header("Content-Disposition:   attachment;   filename= " . $fileName);
            //   输出文件内容
            echo fread($file, filesize($filePath));
            fclose($file);
            exit;
        }
    }

    /**
     * 单个回显锚点
     * @return \think\response\Json
     * @author hutao
     */
    public function getAnchorPoint()
    {
        // 前台需要传递 的是 锚点的名称 anchorName
        if($this->request->isAjax()){
            $param = input('param.');
            // 验证规则
            $rule = [
                ['anchorName', 'require', '请选择要回显的锚点名称']
            ];
            $validate = new \think\Validate($rule);
            //验证部分数据合法性
            if (!$validate->check($param)) {
                return json(['code' => -1,'msg' => $validate->getError()]);
            }
            $name = $param['anchorName'];
            $pic = new AnchorPointModel();
            $flag = $pic->getAnchorTb($name);
            return json($flag);
        }
    }

    /**
     * 全部回显锚点
     * @return \think\response\Json
     * @author hutao
     */
    public function allAnchorPoint()
    {
        // 前台需要传递 的是 锚点的名称 anchorName
        if($this->request->isAjax()){
            $pic = new AnchorPointModel();
            $flag = $pic->getAnchorTb();
            return json($flag);
        }
    }

    /**
     * 管理3D模板首页
     * @return mixed
     */
    public function manage()
    {
        return $this->fetch('manage');
    }

    //查看控制点页面
    public function controll()
    {
        return $this->fetch('controll');
    }

    /**
     * 管理3D-统计未验评、优良、合格
     * @return mixed
     */
    public function countUnit()
    {
        //实例化模型类
        $model =  new PictureModel();
        $unit_data = $model->getAllCountUnit();
        //定义一个空的数组
        $data = array();
        if(!empty($unit_data))
        {
            foreach($unit_data as $key=>$val)
            {
                //根据project_cate的类型查询相应的表中的数据

                switch($val["project_cate"])
                {
                    case "洞挖":
                        $result_rate = $model->getCateKaiwaRate($val["project_id"]);
                        $unit_data[$key]["quality_level"] = $result_rate["quality_level"];
                        break;
                    case "明挖":
                        $result_rate = $model->getCateKaiwaRate($val["project_id"]);
                        $unit_data[$key]["quality_level"] = $result_rate["quality_level"];
                        break;
                    case "支护":
                        $result_rate = $model->getCateZhiRate($val["project_id"]);
                        $unit_data[$key]["quality_level"] = $result_rate["quality_level"];
                        break;
                    case "混凝土":
                        $result_rate = $model->getCateHunRate($val["project_id"]);
                        $unit_data[$key]["quality_level"] = $result_rate["quality_level"];
                        break;
                    case "排水孔":
                        $result_rate = $model->getCateScupperRate($val["project_id"]);
                        $unit_data[$key]["quality_level"] = $result_rate["quality_level"];
                        break;
                }
            }

            $total = count($unit_data);//总数，未验评+优良+合格

            $count = array_count_values(array_column($unit_data,"quality_level"));

            //尚未评定、合格、优良
            $data["excellent_number"] = empty($count["优良"]) ? 0 : $count["优良"];//优良数量
            $data["qualified_number"] = empty($count["合格"]) ? 0 : $count["合格"];//合格数量

            $data["excellent_rate"] = round($data["excellent_number"] / $total * 100);//优良率
            $data["qualified_rate"] = round($data["qualified_number"] / $total * 100);//合格率

            //定义两个空数组表示优良、合格
            $excellent = array();
            $qualified = array();

            foreach ($unit_data as $key => $val)
            {
                switch($val["quality_level"])
                {
                    case "优良":
                        $excellent[] = $val;
                        break;
                    case "合格":
                        $qualified[] = $val;
                        break;
                }
            }
        }else
        {
            $excellent = [];
            $qualified = [];
            $data = [];
        }

        //查询全部的模型文件

        $model_picture = $model->getAllModelPic();

        if(empty($model_picture))
        {
            $model_picture = [];
        }
        return json(["code"=>1,"excellent"=>$excellent,"qualified"=>$qualified,"data"=>$data,"model_picture"=>$model_picture]);
    }

    /**
     * 管理3D-管理信息
     * @return \think\response\Json
     */
    public function managementInfo()
    {
        //前台需要传过来picture_number模型图编号
        if($this->request->isAjax()){
            //实例化模型类
            $model =  new PictureModel();
            $picture_number = input('post.picture_number');

            /*******基本信息**********/
            $unit_info = $model->getUnitInfo($picture_number);

            if(empty($unit_info))
            {
                $unit_info = [];
            }
            return json(["code"=>1,"unit_info"=>$unit_info]);
        }
    }
}

