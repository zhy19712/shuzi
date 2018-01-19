<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:74:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\reform\index.html";i:1516331301;s:75:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\public\header.html";i:1516331301;s:75:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\public\footer.html";i:1516331301;}*/ ?>
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
    input{
        background-color: transparent !important;
    }

    .dataTables_wrapper{
        margin-top: 20px !important;
    }
        .mybtn{
            margin-top: -5px !important;
        }
    </style>
</head>
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/webuploader.css">
<link rel="stylesheet" type="text/css" href="/static/admin/webupload/style.css">
<link rel="stylesheet" href="/static/admin/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/static/admin/css/jedate.css">
<link rel="stylesheet" href="/static/admin/css/iconfont.css">
<link rel="stylesheet" href="/static/admin/css/zTreeStyle/zTreeStyle.css">
<style>
    #upload,#upload2,#upload3,#upload4,#upload5,#upload6,#upload7,#upload8,#upload9,#upload10,#upload15,#upload12,#upload13,#upload14,#upload99{
        position: absolute;
        right: 40px;
        margin-top: 8px;
        width: 88px;
        height: 30px;
        text-align: center;
        color: #fff;
        line-height: 30px;
        cursor: pointer;
        background-color: #00a2d4;
    }
    #mylist{
        float: right;
        font-size: 14px;
        font-weight: 700;
    }
    #upload_table_wrapper{
        padding: 0;
        padding-bottom: 3px;
    }
    #upload_table{
        margin-top: 46px;
    }
    .mytable{
        width: 100%;
        text-align: center;
        line-height: 34px;
        border-top: 1px solid #000;
        border-left: 1px solid #000;
    }
    .mytable td{
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
        padding: 0 10px;
    }
    .mytable td input{
        width: 100%;
        line-height: 20px;
        margin-bottom: 5px;
    }
    #content{
        width: 96%;
        margin: 10px auto;
        position: relative;
    }
    .mybtn{
        float: left;
        margin-right: 20px;
    }
    #qc_table td,#qc_table th{
        text-align: center;
    }
    #container,#container2{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background-color: #fff;
    }
    #home>div{
        margin-top: 20px;
    }
    .myheader{
        height: 30px;
        line-height: 30px;
        text-align: center;
        margin-bottom: 10px;
    }
    .myheader p{
        font-weight: 700;
    }
    #mysave3,#mysave2,#mysave1,#mysave4{
        position: absolute;
        right: 20px;
        margin-top: 8px;
        width: 88px;
        height: 30px;
        text-align: center;
        color: #fff;
        line-height: 30px;
        margin-right: 20px;
        cursor: pointer;
        background: url("__IMG__/bg_1.png") no-repeat center center;
    }
    .exp{
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
    }
    .exp:first-child{
        color: #ff9933;
    }
    .exp>span{
        display: inline-block;
        vertical-align: middle;
        font-size: 18px;
    }
    #flow_content>div{
        display: none;
    }
    #flow_content>div:first-child{
        display: block;
    }
    .myp{
        background-color: #bae2f3;
        padding: 8px 0;
        position: absolute;
        width: 100%;
    }
    .myp1{
        display: inline-block;
        vertical-align: middle;
        background-color: #bae2f3;
        padding: 3px 10px;
        line-height: 30px;
        margin-bottom: 0;
        font-weight: 700;
        border-right: 1px solid #000;
    }
    .myinput{
        vertical-align: top;
        line-height: 20px;
        margin-top: 5px;
        margin: 5px 10px 0 10px;
    }
    #info>div{
        border: 1px solid #000;
    }
    #info>.top{
        margin-top: 20px;
        border-bottom: none;
    }
    #info>.bottom{
        margin-bottom: 20px;
    }
    #upload_wrapper #table_wrapper{
        height: 150px;
        overflow: scroll;
        width: 100%;
    }
    #container,#container2{
        display: none;
    }
    #img_show img{
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }
    #tab_li{
        font-size: 0;
    }
    #tab_li>div{
        display: inline-block;
        vertical-align: middle;
        width: 50%;
        text-align: center;
        border: 1px solid #ccc;
        font-size: 14px;
        line-height: 30px;
        cursor: pointer;
    }
    #tab_li>div:first-child{
        background-color: #bae2f3;
    }
    #tab_con>div:nth-child(2){
        display: none;
    }
    .ibox-content>div{
        margin-top: 20px;
    }
    .ibox-content label{
        text-align: right;
    }
    #ssgy{
        display: none;
    }
    #uploadimg>div:nth-child(2),#uploadimg2>div:nth-child(2){
        width: 90px !important;
        height: 100% !important;
    }
    #bhg{
        display: none;
    }
</style>
<body class="gray-bg animated fadeInRight" style="background-color:#fff;">
<div id="content">
    <div id="standard_wrapper">
        <table id="standard_table" width="100%" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>内容描述</th>
                <th>类别</th>
                <th>监理责任人</th>
                <th>检查时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
        </table>
    </div>
    <div id="container">
        <button class="btn btn-primary" id="back">返回</button>
        <div class="tab-pane" id="profile">
            <div id="flow">
                <div class="exp">
                    <span>质量缺陷描述</span>
                    <img src="__IMG__/arr.png" alt="">
                </div>
                <div class="exp">
                    <span>整改处理措施</span>
                    <img src="__IMG__/arr.png" alt="">
                </div>
                <div class="exp">
                    <span>检查</span>
                </div>
            </div>
            <div id="flow_content">
                <div id="step7">
                    <div id="check">
                        <div style="height: 50px;">
                            <div id="list9"></div>
                            <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>质量缺陷描述</p>
                            <div id="mysave1">保存</div>
                        </div>
                        <textarea name="description" id="sdescription" style="width: 100%;min-height: 100px;"></textarea>
                        <div style="height: 50px;">
                            <div id="list10"></div>
                            <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>附件</p>
                            <div id="upload10">上传文件</div>
                        </div>
                        <div id="check_table_wrapper2">
                            <table id="check_table2" width="100%" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>上传人</th>
                                    <th>上传时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="step8">
                    <div id="implement">
                        <div style="height: 50px;">
                            <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>整改处理措施</p>
                            <div id="mysave2">保存</div>
                        </div>
                        <textarea name="method" id="smethod" style="min-height: 100px;width: 100%;"></textarea>
                        <div id="info">
                            <div class="top">
                                <p class="myp1">要求整改、处理时间</p>
                                <input type="text" name="date_reform_design" id="sdate_reform_design" class="myinput">
                            </div>
                            <div class="bottom">
                                <p class="myp1">监理负责人</p>
                                <input type="text" name="owner1" id="sowner1" class="myinput">
                                <p class="myp1" style="border-left: 1px solid #000;">标段负责人</p>
                                <input type="text" name="owner2" id="sowner2" class="myinput">
                            </div>
                        </div>
                        <div style="height: 50px;">
                            <div id="list7"></div>
                            <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>备注</p>
                            <div id="upload7">上传文件</div>
                        </div>
                        <div id="implement_table_wrapper1">
                            <table id="implement_table1" width="100%" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>上传人</th>
                                    <th>上传时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="step9">
                    <div id="standard">
                        <div style="height: 50px;">
                            <div id="list12"></div>
                            <p id="title" class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>检查</p>
                        </div>
                        <div>
                            <select name="unq" id="unq" class="form-control" style="width: 100px;display: inline-block;">
                                <option value="合格">合格</option>
                                <option value="不合格">不合格</option>
                            </select>
                            <div id="mysave3" style="display: inline-block;right: 10px;">保存</div>
                        </div>
                        <div id="hg">
                            <div style="border: 1px solid #000;margin: 20px 0;">
                                <p class="myp1">实际整改、处理时间</p>
                                <input type="text" name="date_reform_actual" id="sdate_reform_actual" class="myinput">
                            </div>
                            <div id="uploadimg"></div>
                            <div id="img_container" style="height: 300px;">
                                <img src="" alt="" style="display: block;margin: 0 auto;max-height: 300px;">
                            </div>
                            <textarea name="reform_image_description" id="sreform_image_description" style="width: 100%;min-height: 100px;"></textarea>
                            <div style="height: 50px;">
                                <div id="list13"></div>
                                <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>附件资料</p>
                                <div id="upload13">上传文件</div>
                            </div>
                            <div id="standard_table_wrapper2">
                                <table id="standard_table2" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>名称</th>
                                        <th>上传人</th>
                                        <th>上传时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div id="bhg">
                            <div style="border: 1px solid #000;margin: 20px 0;">
                                <p class="myp1">检查-不合格时间</p>
                                <input type="text" name="date_unqualified" id="sdate_unqualified" class="myinput">
                            </div>
                            <div id="uploadimg2"></div>
                            <div id="img_container2" style="height: 300px;">
                                <img src="" alt="" style="display: block;margin: 0 auto;max-height: 300px;">
                            </div>
                            <div style="height: 50px;">
                                <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>不合格情况描述</p>
                            </div>
                            <textarea name="unqualified_image_description" id="sunqualified_image_description" style="width: 100%;min-height: 100px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="container2">
        <button class="btn btn-primary" id="back2">返回</button>
        <div style="height: 50px;">
            <div></div>
            <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>质量事故描述</p>
            <div id="mysave4">保存</div>
        </div>
        <textarea name="accident_description" id="saccident_description" style="width: 100%;min-height: 100px;"></textarea>
        <div style="height: 50px;">
            <div id="list15"></div>
            <p class='myp' style='line-height:30px;font-weight: 700;text-align: center;'>附件</p>
            <div id="upload15">上传文件</div>
        </div>
        <div id="fj_wrapper">
            <table id="fj_table" width="100%" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>上传人</th>
                    <th>上传时间</th>
                    <th>操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="form_container" style="display: none;"></div>

<div id="standard_modal" class="modal animated fadeInRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 60%;margin: 0 auto;">
    <div class="row">
        <div class="col-sm-12" style="background-color:#fff;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5></h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" name="standardAdd" id="standardAdd" method="post" action="<?php echo url('ReformAdd'); ?>">
                        <input type="text" name="id" id="id1" style="display: none;">
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label">质量问题类别：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <select name="cate" id="scate" class="form-control" >
                                        <option value="质量缺陷">质量缺陷</option>
                                        <option value="质量事故">质量事故</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label">检查时间：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="sdate_check" type="text" class="form-control" name="date_check" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label">内容描述：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <textarea name="name" id="sname" class="form-control" style="width: 100%;min-height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-offset-5">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 返回</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ss_modal" class="modal animated fadeInRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 60%;margin: 0 auto;">
    <div class="row">
        <div class="col-sm-12" style="background-color:#fff;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5></h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" name="pro_modalAdd" id="pro_modalAdd" method="post" action="<?php echo url('prototypeListAdd'); ?>">
                        <input type="text" name="id" id="id2" style="display: none;">
                        <input type="text" name="group_id" id="group_id2" style="display: none;">
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label">工程部位：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="ppart" type="text" class="form-control" name="part" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label">内容：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <textarea name="content" id="pcontent" style="width: 100%;min-height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label">责任人：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="powner" type="text" class="form-control" name="owner" placeholder="请在下方树结构选取责任人" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-lg-12 col-xs-12">
                                <label class="col-xs-3 control-label"></label>
                                <div class="myblock col-xs-8 input-group">
                                    <div id="tree_container" class="ztree"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-offset-5">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 返回</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="img_show" class="modal animated fadeInRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 60%;margin: 0 auto;">
    <div class="row" style="height: 90%;">
        <div class="col-sm-12" style="height: 100%;">
            <div class="ibox float-e-margins" style="height: 100%;">
                <div class="ibox-content" style="height: 100%;">
                    <img src="" alt="" style="max-height: 85%;">
                    <a class="btn btn-danger" data-dismiss="modal" style="position: absolute;bottom: 10px;left: 50%;margin-left: -30px;"><i class="fa fa-close"></i> 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="fname" class="modal animated fadeInRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 60%;margin: 0 auto;">
    <div class="row">
        <div class="col-sm-12" style="background-color:#fff;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5></h5>
                </div>
                <div class="ibox-content">
                    <div class="col-lg-12 col-xs-12">
                        <label class="col-xs-3 control-label" id="file">文件：</label>
                        <div class="myblock col-xs-8 input-group">
                            <div id="file_per" style="display: inline-block;vertical-align: middle;"></div>
                            <div id="file_upload" style="display: inline-block;vertical-align: middle;"></div>
                            <div id="file_list" style="display: inline-block;vertical-align: middle;"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-offset-6">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 返回</a>
                    </div>
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
<script src="__JS__/jquery.ztree.all.js"></script>
<script src="__JS__/jquery.dataTables.min.js"></script>
<script src="__JS__/jquery.jedate.js"></script>
<script>
    var uploader;
    var ss_table;
    var cid,cid3;
    var mytablename;
    var path1,path2;
    var uploaderimg = WebUploader.create({
        auto: true,// 选完文件后，是否自动上传。
        swf: '/static/admin/webupload/Uploader.swf',// swf文件路径
        server: "<?php echo url('Upload/uploadReform'); ?>",// 文件接收服务端。
        chunked: false,
        fileSizeLimit: 1000 *1024 *1024,
        formData: {"uid":"","table_name":"jc"},
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: {
            multiple: false,
            id: "#uploadimg",
            innerHTML: "上传效果图"
        },
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        }
    });
    // 文件上传成功
    uploaderimg.on( 'uploadSuccess', function( file,data, response ) {
        console.log(file)
        console.log(data)
        if(data.code === 1){
            layer.msg(data.msg,{icon:1,time:1500,shade: 0.1});
            path1 = data.path.substr(1);
            $("#img_container img").attr('src',path1);
        }else {
            layer.msg("抱歉，您没有此权限");
        }
    });
    uploaderimg.on("uploadStart",function () {
        uploaderimg.options.formData.uid = cid;
    });

    // 文件上传失败，显示上传出错。
    uploaderimg.on( 'uploadError', function( file ) {
        layer.msg("上传失败",{icon:2,time:1500,shade: 0.1});
    });


    var uploaderimg2 = WebUploader.create({
        auto: true,// 选完文件后，是否自动上传。
        swf: '/static/admin/webupload/Uploader.swf',// swf文件路径
        server: "<?php echo url('Upload/uploadReform'); ?>",// 文件接收服务端。
        chunked: false,
        fileSizeLimit: 1000 *1024 *1024,
        formData: {"uid":"","table_name":"jcbhg"},
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: {
            multiple: false,
            id: "#uploadimg2",
            innerHTML: "上传效果图"
        },
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/jpg,image/jpeg,image/png'
        }
    });
    // 文件上传成功
    uploaderimg2.on( 'uploadSuccess', function( file,data, response ) {
        console.log(file)
        console.log(data)
        if(data.code === 1){
            layer.msg(file.name + "上传成功",{icon:1,time:1500,shade: 0.1});
            path2 = data.path.substr(1);
            $("#img_container2 img").attr('src',path2);
        }else {
            layer.msg("抱歉，您没有此权限");
        }
    });
    uploaderimg2.on("uploadStart",function () {
        uploaderimg2.options.formData.uid = cid;
    });

    // 文件上传失败，显示上传出错。
    uploaderimg2.on( 'uploadError', function( file ) {
        layer.msg("上传失败",{icon:2,time:1500,shade: 0.1});
    });
    function imgshow(n) {
        if(n === 1){
            $("#img_show img").attr('src',path1);
        }else if(n === 2){
            $("#img_show img").attr('src',path2);
        }
        $("#img_show").modal('show');
    }
    $("#img_container img").click(function () {
        imgshow(1);
    });
    $("#img_container2 img").click(function () {
        imgshow(2);
    });
    var kind;
    $("#unq").change(function () {
        kind = $(this).val();
        if(kind === "合格"){
            $("#title").text("检查");
            $("#hg").css("display","block");
            $("#bhg").css("display","none");
        }else {
            $("#title").text("检查-不合格");
            $("#hg").css("display","none");
            $("#bhg").css("display","block");
        }
    })
    function table_refresh() {
       if(mytablename === "bzhgysfp"){
            check_table2.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bzhgysfp").load();
        }else if(mytablename === "ss"){
            implement_table1.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=ss").load();
        }else if(mytablename === "bhg"){
            standard_table2.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bhg").load();
        }else if(mytablename === "fj"){
           fj_table.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=fj").load();
       }
    }

    function myupload() {
        $("#fname input").val("");
        $("#file_list").empty();
        $("#fname").modal('show');
        if(!uploader){
            uploader = WebUploader.create({
                auto: false,// 选完文件后，是否自动上传。
                swf: '/static/admin/webupload/Uploader.swf',// swf文件路径
                server: "<?php echo url('Upload/uploadReformAttachment'); ?>",// 文件接收服务端。
                chunked: false,
                fileSizeLimit: 1000 *1024 *1024,
                fileNumLimit: 1,
                formData: {"uid":"","table_name":"","group_id":""},
                duplicate :true,// 重复上传图片，true为可重复false为不可重复
                pick: {
                    multiple: false,
                    id: "#file_upload",
                    innerHTML: "上传文件"
                }
            });

            uploader.on( 'fileQueued', function( file ) {
                $("#file_per").append('<div id="' + file.id + '" class="item">' +
                    '</div>');
                $("#file_list").append('<div style="display: inline-block;vertical-align: middle;" class="' + file.id + '" class="item">' +
                    '<span class="info">' + file.name + '</span>&nbsp' +
                    '<span class="webuploadDelbtn" style="color: #0d8ddb;text-decoration: underline;cursor: pointer;">删除</span>' +
                    '</div>' );
            });
            $("#file_list").on("click", ".webuploadDelbtn", function () {
                var $ele = $(this);
                var id = $ele.parent().attr("class");
                var file = uploader.getFile(id);
                uploader.removeFile(file,true);
            });
            uploader.on('fileDequeued', function (file) {
                $(file.id).remove();
                $('.'+file.id).remove();
                console.log("remove");
            });
            uploader.on("uploadStart",function () {
                uploader.options.formData.table_name = mytablename;
                uploader.options.formData.group_id = cid;
            });
            uploader.on('beforeFileQueued', function (file) {
                if($("#file_list").html()){
                    alert("只能上传一个文件,请先删除文件");
                    return false;
                }else {
                    return true;
                }
            });
            // 文件上传过程中创建进度条实时显示。
            uploader.on('uploadProgress', function (file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress .progress-bar');
                // 避免重复创建
                if (!$percent.length) {
                    $percent = $('<div class="progress progress-striped active">' +
                        '<div class="progress-bar" role="progressbar" style="width: 0%">' +
                        '</div>' +
                        '</div>').appendTo($li).find('.progress-bar');
                }
                $li.find('p.state').text('上传中');
                $percent.css('width', percentage * 100 + '%');
            });
            // 文件上传成功
            uploader.on( 'uploadSuccess', function( file,data, response ) {
                console.log(file)
                console.log(data)
                if(data.code === 1){
                    layer.msg(file.name + "上传成功",{icon:1,time:1500,shade: 0.1});
                    table_refresh();
                    uploader = null;
                    $("#file_upload").empty();
                    $("#fname").modal('hide');
                    $("#file_per").empty();

                    if(mytablename === "jc"){
                        $.ajax({
                            url: "./prototypeListAdd",
                            type: "post",
                            data: {id:cid3,status:"检查"},
                            dataType: "json",
                            success: function (res) {
                                console.log(res);
                            }
                        })
                    }
                    if(mytablename === "chhzb"){
                        $.ajax({
                            url: "./prototypeListAdd",
                            type: "post",
                            data: {id:cid3,status:"完成"},
                            dataType: "json",
                            success: function (res) {
                                console.log(res);
                            }
                        })
                    }
                }else {
                    layer.msg("抱歉，您没有此权限");
                }

            });

            // 文件上传失败，显示上传出错。
            uploader.on( 'uploadError', function( file ) {
                layer.msg("上传失败",{icon:2,time:1500,shade: 0.1});
                $("#file_per").empty();
                $("#file_upload").empty();
            });
        }
    }
    $("#upload10").click(function () {
        mytablename = "bzhgysfp";
        myupload();
    });
    $("#upload7").click(function () {
        mytablename = "ss";
        myupload();
    });
    $("#upload13").click(function () {
        mytablename = "bhg";
        myupload();
    });
    $("#upload15").click(function () {
        mytablename = "fj";
        myupload();
    });

    //日期插件
    $("#sdate_check").jeDate({
        format: "YYYY-MM-DD"
    });
    $("#sdate_reform_design").jeDate({
        format: "YYYY-MM-DD"
    });
    $("#sdate_reform_actual").jeDate({
        format: "YYYY-MM-DD"
    });
    $("#sdate_unqualified").jeDate({
        format: "YYYY-MM-DD"
    });
    //标准工艺
    function edit(that) {
        var id = $(that).parent("td").parent("tr").children("td:first-child").text();

        $.ajax({
            url: "./index",
            type: "post",
            data: {id:id},
            dataType: "json",
            success: function (res) {
                console.log(res);
                if(res.code === 1){
                    $("#id1").val(id);
                    $("#standard_modal h5").text("编辑");
                    $("#standard_modal").modal("show");
                    $("#sname").val(res.data.name);
                    $("#scate").val(res.data.cate);
                    $("#sdate_check").val(res.data.date_check);
                }else {
                    layer.msg(res.msg);
                }

            }
        })
    }
    function del(that) {
        var id = $(that).parent("td").parent("tr").children("td:first-child").text();
        layer.confirm('确认删除此条记录吗?', {icon: 3, title:'提示'}, function(index){
            $.ajax({
                url: "./ReformDel",
                data: {'id' : id},
                type: "post",
                dataType: "json",
                success: function (res) {
                    if(res.code == 1){
                        layer.msg(res.msg,{icon:1,time:1500,shade: 0.1});
                        standard_table.ajax.url("__SCRIPT__/reform.php").load();
                    }else{
                        layer.msg(res.msg,{icon:0,time:1500,shade: 0.1});
                    }
                }
            })
            layer.close(index);
        })
    }
    function detail(that) {
        var id = $(that).parent("td").parent("tr").children("td:first-child").text();
        cid = id;
        var cate = $(that).parent("td").parent("tr").children("td:nth-child(3)").text();
            $.ajax({
                url: "./index",
                type: "post",
                data: {id:id},
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    if(res.code === 1){
                        if(cate === "质量缺陷"){
                            $("#sdescription").val(res.data.description);
                            $("#smethod").val(res.data.method);
                            $("#sdate_reform_design").val(res.data.date_reform_design);
                            $("#sowner1").val(res.data.owner1);
                            $("#sowner2").val(res.data.owner2);
                            $("#sdate_reform_actual").val(res.data.date_reform_actual);
                            $("#sreform_image_description").val(res.data.reform_image_description);
                            $("#sdate_unqualified").val(res.data.date_unqualified);
                            $("#sunqualified_image_description").val(res.data.unqualified_image_description);
                            if(res.data.reform_image_path){
                                path1 = res.data.reform_image_path.substr(1);
                                $("#img_container img").attr('src',path1);
                            }
                            if(res.data.unqualified_image_path){
                                path2 = res.data.unqualified_image_path.substr(1);
                                $("#img_container2 img").attr('src',path2);
                            }
                            if(res.data.qualified === "不合格"){
                                $("#hg").css("display","none");
                                $("#bhg").css("display","block");
                                $("#unq").val("不合格");
                            }
                            check_table2.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bzhgysfp").load();
                            implement_table1.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=ss").load();
                            standard_table2.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bhg").load();
                            $("#container").css("display","block");
                        }else {
                            $("#saccident_description").val(res.data.accident_description);
                            fj_table.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=fj").load();
                            $("#container2").css("display","block");
                        }
                    }else {
                        layer.msg(res.msg);
                    }
                }
            });

    }
    var standard_table = $('#standard_table').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "__SCRIPT__/reform.php"
        },
        "dom": '<"mybtn btn btn-outline btn-primary"><"myl"l>frtip',
        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": [6],
                "render" :  function(data,type,row) {
                    var html = "<input type='button' class='btn btn-info btn-outline btn-xs' style='margin-left: 5px;' onclick='detail(this)' value='详情'/>";
                    html += "<input type='button' class='btn btn-info btn-outline btn-xs' style='margin-left: 5px;' onclick='edit(this)' value='编辑'/>";
                    html += "<input type='button' class='btn btn-danger btn-outline btn-xs' style='margin-left: 5px;' onclick='del(this)' value='删除'/>" ;
                    return html;
                }
            }
        ],
        "language": {
            "lengthMenu": "每页_MENU_ 条记录",
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "search": "搜索：",
            "infoFiltered": "(从 _MAX_ 条记录过滤)",
            "paginate": {
                "previous": "上一页",
                "next": "下一页"
            }
        }
    } );
    $("div.mybtn").html('<div>新增</div>');
    $("#standard_wrapper .mybtn").click(function () {
        $("#standard_modal input").val("");
        $("#standard_modal textarea").val("");
        $("#standard_modal h5").text("新增");
        $("#standard_modal").modal('show');
    });
    $("#back").click(function () {
        $("#container").css("display","none");
        standard_table.ajax.url("__SCRIPT__/reform.php").load();
    })
    $("#back2").click(function () {
        $("#container2").css("display","none");
        standard_table.ajax.url("__SCRIPT__/reform.php").load();
    })


    //流程tab
    $(".exp>span").click(function () {
        var index = $(this).parent("div").index();
        $(".exp>span").css("color","#000");
        $(this).css("color","#ff9933");
        console.log(index)
        $("#flow_content>div").css("display","none").eq(index).css("display","block");
    })

    //文件删除
    var file_name3,file_name4;
    function fdelatt(that) {
        var id = $(that).parent("div").attr("id");
        $.ajax({
            url: "./prototypeAttachmentEditDel",
            type: "post",
            data: {id:id},
            datatype: "json",
            success: function (res) {
                if(res.code === 1){
                    $("#"+id).remove();
                }else {
                    layer.msg(res.msg);
                }
            }
        })
    }
    function fdel(that) {
        var id = $(that).parent("td").parent("tr").children("td:first-child").text();
        var parent = $(that).parent("td").parent("tr").parent("tbody").parent("table").attr("id");
        layer.confirm('确认删除此条记录吗?', {icon: 3, title:'提示'}, function(index){
            $.ajax({
                url: "./attachmentDel",
                data: {'id' : id},
                type: "post",
                dataType: "json",
                success: function (res) {
                    if(res.code == 1){
                        layer.msg(res.msg,{icon:1,time:1500,shade: 0.1});
                        if(parent === "check_table2"){
                            check_table2.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bzhgysfp").load();
                        }else if(parent === "implement_table1"){
                            implement_table1.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=ss").load();
                        }else if(parent === "standard_table2"){
                            standard_table2.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bhg").load();
                        }else if(parent === "fj_table"){
                            fj_table.ajax.url("__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=fj").load();
                        }
                    }else{
                        layer.msg(res.msg,{icon:0,time:1500,shade: 0.1});
                    }
                }
            })
            layer.close(index);
        })
    }
    function fdownload(that) {
        $.ajax({
            url: "./attachmentDownload",
            success: function (res) {
                console.log(res);
                if(res.code != 1){
                    layer.msg(res.msg);
                }else {
                    $("#form_container").empty();
                    var id = $(that).parent("td").parent("tr").children("td:first-child").text();
                    var str = "";
                    str += ""
                        + "<iframe name=downloadFrame"+ id +" style='display:none;'></iframe>"
                        + "<form name=download"+ id +" action='./attachmentDownload' method='get' target=downloadFrame"+ id +">"
                        + "<span class='file_name' style='color: #000;'>"+str+"</span>"
                        + "<input class='file_url' style='display: none;' name='id' value="+ id +">"
                        + "<button type='submit' class=btn" + id +"></button>"
                        + "</form>"
                    $("#form_container").append(str);
                    $("#form_container").find(".btn" + id).click();
                }
            }
        })
    }
    $("#fname .btn-primary").click(function () {
        if($("#file_list").html()){
            if(!$("#id4").val()){
                uploader.upload();
            }else {
                if(file_name3 === file_name4){
                    $.ajax({
                        url: "./editPrototypeAttachmentNoUpload",
                        type: "post",
                        data: {uid:$("#id4").val(),remark:$("#fremark").val(),group_id:cid3,table_name:mytablename},
                        datatype: "json",
                        success: function (res) {
                            if(res.code === 1){
                                layer.msg(res.msg,{icon:1,time:1500,shade: 0.1});
                                table_refresh();
                                $("#fname").modal('hide');
                            }else {
                                layer.msg(res.msg,{icon:1,time:1500,shade: 0.1});
                            }
                        }
                    })
                }else {
                    uploader.upload();
                }
            }
        }else {
            alert("请选择上传文件");
        }
    })


    var check_table2 = $('#check_table2').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bzhgysfp",
            "async": false
        },
        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": [4],
                "render" :  function(data,type,row) {
                    var html = "<input type='button' class='btn btn-info btn-outline btn-xs' style='margin-left: 5px;' onclick='fdownload(this)' value='下载'/>";
                    html += "<input type='button' class='btn btn-danger btn-outline btn-xs' style='margin-left: 5px;' onclick='fdel(this)' value='删除'/>" ;
                    return html;
                }
            }
        ],
        "language": {
            "lengthMenu": "每页_MENU_ 条记录",
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "search": "搜索：",
            "infoFiltered": "(从 _MAX_ 条记录过滤)",
            "paginate": {
                "previous": "上一页",
                "next": "下一页"
            }
        }
    } );

    var implement_table1 = $('#implement_table1').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=ss",
            "async": false
        },
        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": [4],
                "render" :  function(data,type,row) {
                    var html = "<input type='button' class='btn btn-info btn-outline btn-xs' style='margin-left: 5px;' onclick='fdownload(this)' value='下载'/>";
                    html += "<input type='button' class='btn btn-danger btn-outline btn-xs' style='margin-left: 5px;' onclick='fdel(this)' value='删除'/>" ;
                    return html;
                }
            }
        ],
        "language": {
            "lengthMenu": "每页_MENU_ 条记录",
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "search": "搜索：",
            "infoFiltered": "(从 _MAX_ 条记录过滤)",
            "paginate": {
                "previous": "上一页",
                "next": "下一页"
            }
        }
    } );

    var standard_table2 = $('#standard_table2').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=bhg",
            "async": false
        },
        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": [4],
                "render" :  function(data,type,row) {
                    var html = "<input type='button' class='btn btn-info btn-outline btn-xs' style='margin-left: 5px;' onclick='fdownload(this)' value='下载'/>";
                    html += "<input type='button' class='btn btn-danger btn-outline btn-xs' style='margin-left: 5px;' onclick='fdel(this)' value='删除'/>" ;
                    return html;
                }
            }
        ],
        "language": {
            "lengthMenu": "每页_MENU_ 条记录",
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "search": "搜索：",
            "infoFiltered": "(从 _MAX_ 条记录过滤)",
            "paginate": {
                "previous": "上一页",
                "next": "下一页"
            }
        }
    } );

    var fj_table = $('#fj_table').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "__SCRIPT__/reform_attachment.php?group_id=" + cid + "&table_name=fj",
            "async": false
        },
        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": [4],
                "render" :  function(data,type,row) {
                    var html = "<input type='button' class='btn btn-info btn-outline btn-xs' style='margin-left: 5px;' onclick='fdownload(this)' value='下载'/>";
                    html += "<input type='button' class='btn btn-danger btn-outline btn-xs' style='margin-left: 5px;' onclick='fdel(this)' value='删除'/>" ;
                    return html;
                }
            }
        ],
        "language": {
            "lengthMenu": "每页_MENU_ 条记录",
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "search": "搜索：",
            "infoFiltered": "(从 _MAX_ 条记录过滤)",
            "paginate": {
                "previous": "上一页",
                "next": "下一页"
            }
        }
    } );

    //提交
    //standard_table
    $("#standard_modal .btn-primary").click(function () {
        $('#standardAdd').ajaxForm({
            success: function (data) {
                if(data.code==1){
                    $("#standard_modal").modal('hide');
                    layer.msg(data.msg);
                    standard_table.ajax.url("__SCRIPT__/reform.php").load();
                }else{
                    layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                    return false;
                }
            },
            dataType: 'json'
        });
    })
    //sstable
    $("#ss_modal .btn-primary").click(function () {
        $('#pro_modalAdd').ajaxForm({
            success: function (data) {
                if(data.code==1){
                    $("#ss_modal").modal('hide');
                    layer.msg(data.msg);
                    ss_table.ajax.url("__SCRIPT__/prototype_list.php?group_id=" + cid).load();
                }else{
                    layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                    return false;
                }
            },
            dataType: 'json'
        });
    })
    //save
    $("#mysave1").click(function () {
        $.ajax({
            url: "./ReformAdd",
            type: "post",
            data: {id:cid,description:$("#sdescription").val(),status:"整改中"},
            dataType: "json",
            success: function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                }else{
                    layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                    return false;
                }
            }
        })
    })
    $("#mysave2").click(function () {
        $.ajax({
            url: "./ReformAdd",
            type: "post",
            data: {id:cid,method:$("#smethod").val(),date_reform_design:$("#sdate_reform_design").val(),owner1:$("#sowner1").val(),owner2:$("#sowner2").val()},
            dataType: "json",
            success: function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                }else{
                    layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                    return false;
                }
            }
        })
    })
    $("#mysave3").click(function () {
        if(kind === "合格"){
            $.ajax({
                url: "./ReformAdd",
                type: "post",
                data: {id:cid,date_reform_actual:$("#sdate_reform_actual").val(),reform_image_description:$("#sreform_image_description").val(),status:"已整改"},
                dataType: "json",
                success: function (res) {
                    if(res.code==1){
                        layer.msg(res.msg);
                    }else{
                        layer.msg(res.msg, {icon: 5,time:1500,shade: 0.1});
                        return false;
                    }
                }
            })
        }else {
            $.ajax({
                url: "./ReformAdd",
                type: "post",
                data: {id:cid,date_unqualified:$("#sdate_unqualified").val(),unqualified_image_description:$("#sunqualified_image_description").val(),status:"整改中"},
                dataType: "json",
                success: function (res) {
                    if(res.code==1){
                        layer.msg(res.msg);
                    }else{
                        layer.msg(res.msg, {icon: 5,time:1500,shade: 0.1});
                        return false;
                    }
                }
            })
        }

    })
    $("#mysave4").click(function () {
        $.ajax({
            url: "./ReformAdd",
            type: "post",
            data: {id:cid,accident_description:$("#saccident_description").val()},
            dataType: "json",
            success: function (res) {
                if(res.code==1){
                    layer.msg(res.msg);
                }else{
                    layer.msg(res.msg, {icon: 5,time:1500,shade: 0.1});
                    return false;
                }
            }
        })
    })
</script>
</body>
</html>