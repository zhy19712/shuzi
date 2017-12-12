<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"C:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\index\index.html";i:1513065390;s:85:"C:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\public\header.html";i:1513043869;s:85:"C:\phpStudy\PHPTutorial\WWW\shuzi\public/../application/admin\view\public\footer.html";i:1512649436;}*/ ?>
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

<body class="gray-bg" style = "overflow-x: hidden; overflow-y: hidden;">


<!--<script language="javascript"   for="RealBimOcx" EVENT="OnRealBimOcxInited()" type="text/javascript">-->
    <!--RealBimOcx.SwitchBIMSceneSimple("http://192.168.162.128:8008/default.aspx?dir=url_res02&path=","res_jinzhai");-->
    <!--//      RealBimOcx.SwitchBIMSceneSimple("F:\\AppTest\\URL_Res01\\","res_fengning_kw");-->
    <!--RealBimOcx.SetSceVersionInfo(100, -1);-->
<!--</script>-->
<!--<script language="javascript"   for="RealBimOcx" EVENT="WorkCompleteNotification(CompleteEvent,retError)" type="text/javascript">-->
    <!--if(CompleteEvent == "LoadMainScene" && retError==0 ){-->
        <!--RealBimOcx.SetAllHugeObjSubValidState(1);-->
        <!--RealBimOcx.SetSceHugeObjVisible(true);-->
    <!--}-->
<!--</script>-->
<!--<OBJECT id="RealBimOcx" classid="CLSID:2CD7E1BE-10B8-4A1C-B908-4FB7D4FD4ABD"  width="100%" height="100%">-->
<!--</OBJECT>-->

<!--<div id="DominoActionBar" style='position:absolute; left:0px; top:0px; width:100%; height:100%; z-Index:9;opacity: 0;background: transparent;display: none;'>-->
        <!--<iframe id='iframebar' src="about:blank" frameBorder=0 marginHeight=0 marginWidth=0-->
                <!--style="position:absolute; top:0px;left:0px;width:100%;height: 100%; z-Index:-1;background: transparent;">-->
        <!--</iframe>-->
<!--</div>-->




<!--
<div class="wrapper wrapper-content">
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <div>尊敬的管理员<span id="weather"></span></div>
    </div>

    <!-- 上方tab -->

    <!--
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label pull-right">日</span>
                    <span class="label pull-right">周</span>
                    <span class="label label-success pull-right">月</span>
                    <h5>会员</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?php echo $member; ?></h1>
                    <small>今日新增</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label pull-right">日</span>
                    <span class="label pull-right">周</span>
                    <span class="label label-success pull-right">月</span>
                    <h5>订单</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">275,800</h1>
                    <small>总订单</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label pull-right">日</span>
                    <span class="label pull-right">周</span>
                    <span class="label label-success pull-right">月</span>
                    <h5>注册</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">106,120</h1>
                    <small>新增</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <a href="#question">
                        <i class="fa fa-question" style="color:red;margin-left:10px" data-container="body" data-toggle="popover" data-placement="bottom"
                           data-content="日活跃用户 = 当日登录游戏的用户 - 当日新增用户数(去重)@@@@@@@@月活跃用户 = 最近30天登录游戏的用户 - 最近30天新增用户(去重)"></i>
                    </a>
                    <span class="label pull-right">日</span>
                    <span class="label pull-right">周</span>
                    <span class="label label-success pull-right">月</span>
                    <h5>活跃用户</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">80,600</h1>
                    <small>7月</small>
                </div>
            </div>
        </div>
    </div>
    -->

    <!-- 中间折线 -->
<!--
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-cogs"></i> 系统信息
                    </div>
                    <div class="panel-body">
                        <p><i class="fa fa-sitemap"></i> 框架版本：ThinkPHP<?php echo $info['think_v']; ?>
                        </p>
                        <p><i class="fa fa-windows"></i> 服务环境：<?php echo $info['web_server']; ?>
                        </p>
                        <p><i class="fa fa-warning"></i> 上传附件限制：<?php echo $info['onload']; ?>
                        </p>
                        <p><i class="fa fa-credit-card"></i> PHP 版本：<?php echo $info['phpversion']; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-cogs"></i> 系统信息
                    </div>
                    <div class="panel-body">
                        <p><i class="fa fa-send-o"></i> 更多：<a href="http://www.bhidi.com" target="_blank">http://www.bhidi.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
 -->
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
</body>
</html>