<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:84:"C:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\user\useradd.html";i:1512980093;s:85:"C:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\public\header.html";i:1512952060;s:85:"C:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\public\footer.html";i:1512649436;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo config('WEB_SITE_TITLE'); ?></title>
    <link href="/static/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/static/admin/css/plugins/switchery/switchery.css" rel="stylesheet">
    <link href="/static/admin/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="__JS__/themes/default/style.min.css">
    <style type="text/css">
    .long-tr th{
        text-align: center
    }
    .long-td td{
        text-align: center
    }
    </style>
</head>
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/webuploader.css">
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/style.css">
<style>
.file-item{float: left; position: relative; width: 110px;height: 110px; margin: 0 20px 20px 0; padding: 4px;}
.file-item .info{overflow: hidden;}
.uploader-list{width: 100%; overflow: hidden;}
    .myblock{
        display: inline-block;
        vertical-align: middle;
    }
</style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加用户</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!--新增用户信息开始-->
                    <form class="form-horizontal" name="userAdd" id="userAdd" method="post" action="<?php echo url('userAdd'); ?>">
                        <div class="form-group">
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">姓名：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="real_name" type="text" class="form-control" name="real_name" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">性别：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="sex" type="text" class="form-control" name="sex" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <!--<div class="form-group">-->
                            <!--<label class="col-sm-3 control-label">管理员角色：</label>-->
                            <!--<div class="input-group col-sm-4">-->
                                <!--<select class="form-control m-b chosen-select" name="groupid" id="groupid">-->
                                    <!--<option value="">==请选择角色==</option>-->
                                    <!--<?php if(!empty($role)): ?>-->
                                        <!--<?php if(is_array($role) || $role instanceof \think\Collection || $role instanceof \think\Paginator): if( count($role)==0 ) : echo "" ;else: foreach($role as $key=>$vo): ?>-->
                                            <!--<option value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>-->
                                        <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                                    <!--<?php endif; ?>-->
                                <!--</select>-->
                            <!--</div>-->
                        <!--</div>-->
                        <div class="form-group">
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">登录账号：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="username" type="text" class="form-control" name="username" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">登录密码：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="password" type="text" class="form-control" name="password" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">手机号码：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="mobile" type="text" class="form-control" name="mobile" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">办公电话：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="tele" type="text" class="form-control" name="tele" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">所在部门：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="dept" type="text" class="form-control" name="dept" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">职位：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="position" type="text" class="form-control" name="position" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">电子邮箱：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="email" type="text" class="form-control" name="email" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label class="col-xs-2 col-lg-2 control-label">微信账号：</label>
                                <div class="myblock col-xs-10 col-lg-10 input-group">
                                    <input id="wechat" type="text" class="form-control" name="wechat" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-1 control-label">备注：</label>
                                <div class="myblock col-xs-11 input-group">
                                    <!--<textarea id="email" type="text" class="form-control" name="email" >-->
                                    <textarea name="remark" id="remark" class="form-control" style="min-height: 300px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">头像：</label>
                            <div class="input-group col-sm-4">
                                <input type="hidden" id="data_photo" name="portrait" >
                                <div id="fileList" class="uploader-list" style="float:right"></div>
                                <div id="imgPicker" style="float:left">选择头像</div>
                                <img id="img_data" class="img-circle" height="80px" width="80px" style="float:left;margin-left: 50px;margin-top: -10px;" src="/static/admin/images/head_default.gif"/>
                            </div>
                        </div> 
                        <div class="hr-line-dashed"></div>
                        <!--<div class="form-group">-->
                            <!--<label class="col-sm-3 control-label">登录密码：</label>-->
                            <!--<div class="input-group col-sm-4">-->
                                <!--<input id="password" type="text" class="form-control" name="password" >-->
                            <!--</div>-->
                        <!--</div>-->
                        <!--<div class="hr-line-dashed"></div>-->
                        <!--<div class="form-group">-->
                            <!--<label class="col-sm-3 control-label">真实姓名：</label>-->
                            <!--<div class="input-group col-sm-4">-->
                                <!--<input id="real_name" type="text" class="form-control" name="real_name" >-->

                            <!--</div>-->
                        <!--</div>-->
                        <!--<div class="hr-line-dashed"></div>-->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状&nbsp;态：</label>
                            <div class="col-sm-6">
                                <div class="radio i-checks">
                                    <input type="radio" name='status' value="1" checked="checked"/>开启&nbsp;&nbsp;
                                    <input type="radio" name='status' value="0" />关闭
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" href="javascript:history.go(-1);"><i class="fa fa-close"></i> 返回</a>
                            </div>
                        </div>
                    </form>
                    <!--新增用户信息结束-->
                </div>
            </div>

        </div>
    </div>
</div>
<script src="__JS__/jquery.min.js?v=2.1.4"></script>
<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__JS__/content.min.js?v=1.0.0"></script>
<script src="__JS__/plugins/chosen/chosen.jquery.js"></script>
<script src="__JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__JS__/plugins/layer/laydate/laydate.js"></script>
<script src="__JS__/plugins/switchery/switchery.js"></script><!--IOS开关样式-->
<script src="__JS__/jquery.form.js"></script>
<script src="__JS__/layer/layer.js"></script>
<script src="__JS__/laypage/laypage.js"></script>
<script src="__JS__/laytpl/laytpl.js"></script>
<script src="__JS__/lunhui.js"></script>
<script>
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
<script type="text/javascript" src="/static/admin/webupload/webuploader.min.js"></script>

<script type="text/javascript">
    var $list = $('#fileList');
    //上传图片,初始化WebUploader
    var uploader = WebUploader.create({
     
        auto: true,// 选完文件后，是否自动上传。   
        swf: '/static/admin/webupload/Uploader.swf',// swf文件路径 
        server: "<?php echo url('Upload/uploadface'); ?>",// 文件接收服务端。
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: '#imgPicker',// 选择文件的按钮。可选。

        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        },

        'onUploadSuccess': function(file, data, response) {
            $("#data_photo").val(data._raw);
            $("#img_data").attr('src', '/uploads/face/' + data._raw).show();
        }
    });

    uploader.on( 'fileQueued', function( file ) {
        $list.html( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">正在上传...</p>' +
        '</div>' );
    });

    // 文件上传成功
    uploader.on( 'uploadSuccess', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传成功！');
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错!');
    }); 

    //提交
    $(function(){
        $('#userAdd').ajaxForm({
            beforeSubmit: checkForm, 
            success: complete, 
            dataType: 'json'
        });
        
        function checkForm(){
            if( '' == $.trim($('#username').val())){
                layer.msg('请输入用户名',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }
            
            // if( '' == $.trim($('#groupid').val())){
            //     layer.msg('请选择用户角色',{icon:2,time:1500,shade: 0.1}, function(index){
            //     layer.close(index);
            //     });
            //     return false;
            // }

            if( '' == $.trim($('#password').val())){
                layer.msg('请输入登录密码',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }

            if( '' == $.trim($('#real_name').val())){
                layer.msg('请输入真实姓名',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }
        }


        function complete(data){
            if(data.code==1){
                layer.msg(data.msg, {icon: 6,time:1500,shade: 0.1}, function(index){
                    window.location.href="<?php echo url('user/index'); ?>";
                });
            }else{
                layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                return false;   
            }
        }
     
    });



    //IOS开关样式配置
   var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, {
            color: '#1AB394'
        });
    var config = {
        '.chosen-select': {},                    
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

</script>
</body>
</html>