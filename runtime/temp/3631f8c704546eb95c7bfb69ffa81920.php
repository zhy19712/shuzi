<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:85:"D:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\index\editpwd.html";i:1505280616;s:85:"D:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\public\header.html";i:1505280616;s:85:"D:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\public\footer.html";i:1505280616;}*/ ?>
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
    <style type="text/css">
    .long-tr th{
        text-align: center
    }
    .long-td td{
        text-align: center
    }
    </style>
</head>
<body>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">        
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>修改密码</h5>                      
                    </div>
                    <div class="ibox-content">
                        <form class="form-horizontal" name="userAdd" id="userAdd" method="post" action="<?php echo url('editpwd'); ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">当前密码：</label>
                                <div class="col-sm-4">
                                    <input id="old_password" name="old_password" class="form-control" type="password">
                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 请输入当前登录密码</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">新密码：</label>
                                <div class="col-sm-4">
                                    <input id="password" name="password" class="form-control" type="password">
                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 请输入您的密码</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">确认密码：</label>
                                <div class="col-sm-4">
                                    <input id="confirm_password" name="confirm_password" class="form-control" type="password">
                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 请再次输入您的密码</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button id="btnSubmit" class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 提交</button>
                                </div>
                            </div>
                        </form>
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

<script type="text/javascript">
 
    //提交
    $(function(){
        $('#userAdd').ajaxForm({
            beforeSubmit: checkForm, 
            success: complete, 
            dataType: 'json'
        });
        
        function checkForm(){
            if( '' == $.trim($('#old_password').val())){
                layer.msg('请输入旧密码',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }
            if($('#password').val().length<6){
                layer.msg('新密码不能小于6位',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }

            if($('#confirm_password').val()!= $('#password').val()){
                layer.msg('两次密码不一致',{icon:2,time:1500,shade: 0.1}, function(index){
                layer.close(index);
                });
                return false;
            }
        }

        function complete(data){
            if(data.code==1){
                parent.layer.msg(data.msg,{icon: 1,time:2000,shade: 0.1},function(index){
                     window.location.href="<?php echo url('index/index'); ?>";
                });
            }else{
                parent.layer.msg(data.msg, {icon: 2,time:1500,shade: 0.1});
                return false;   
            }
        }
     
    });


</script>
</body>
</html>