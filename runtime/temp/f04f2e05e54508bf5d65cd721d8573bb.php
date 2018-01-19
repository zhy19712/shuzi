<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:80:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\construction\index.html";i:1516344216;s:75:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\public\header.html";i:1516331301;s:75:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\public\footer.html";i:1516331301;}*/ ?>
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
<link rel="stylesheet" href="/static/admin/css/zTreeStyle/zTreeStyle.css">
<link rel="stylesheet" href="/static/admin/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/static/admin/css/jedate.css">
<link rel="stylesheet" href="/static/admin/css/iconfont.css">
<link rel="stylesheet" href="__JS__/dist/plyr.css">
<style>
    .vcontainer{
        display: inline-block;
        width: 20%;
        margin: 5px 2%;
        position: relative;
    }
    .vcontainer input{
        vertical-align: middle;
    }
    .container{
        display: inline-block;
        vertical-align: middle;
        width: 100%;
        position: relative;
        top: 0;
        left: 20px;
    }
    .mask{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 999;
        cursor: pointer;
    }
    .icheckbox_square-green{
        position: absolute !important;
        top: 50% !important;
        display: none;
    }
</style>
<body class="gray-bg" style="background-color:#fff;">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row" id="content">
                <div id="file_per"></div>
                <div id="myheader">
                    <div id="upload" style="display: inline-block;vertical-align: middle;">上传视频文件</div>
                    <button id="edit" class="btn btn-primary">编辑</button>
                    <button id="del" class="btn btn-danger" disabled="disabled">删除</button>
                    <div class="input-group col-md-3 col-sm-6 col-xs-6" style="margin-top:0px;positon:relative;top: -38px;left: 254px;">
                        <input type="text" class="form-control" placeholder="请输入字段名" />
                        <span class="input-group-btn">
                           <button class="btn btn-info btn-search">查找</button>
                        </span>
                    </div>
                </div>
                <div id="mywrapper">
                    <div class="vcontainer">
                        <input type="checkbox" name='status'/>
                        <div class="container">
                            <video controls>
                                <source src="__VIDEO__/123.mp4" type="video/mp4">
                            </video>
                            <div class="mask"></div>
                        </div>
                    </div>
                    <div class="vcontainer">
                        <input type="checkbox" name='status'/>
                        <div class="container">
                            <video controls>
                                <source src="__VIDEO__/other.mp4" type="video/mp4">
                            </video>
                            <div class="mask"></div>
                        </div>
                    </div>
                    <div class="vcontainer">
                        <input type="checkbox" name='status'/>
                        <div class="container">
                            <video controls>
                                <source src="__VIDEO__/123.mp4" type="video/mp4">
                            </video>
                            <div class="mask"></div>
                        </div>
                    </div>
                    <div class="vcontainer">
                        <input type="checkbox" name='status'/>
                        <div class="container">
                            <video controls>
                                <source src="__VIDEO__/岩锚梁施工工法-1213_x264.mp4" type="video/mp4">
                            </video>
                            <div class="mask"></div>
                        </div>
                    </div>
                    <div class="vcontainer">
                        <input type="checkbox" name='status'/>
                        <div class="container">
                            <video controls>
                                <source src="__VIDEO__/岩锚梁施工工法-1213_x264.mp4" type="video/mp4">
                            </video>
                            <div class="mask"></div>
                        </div>
                    </div>
                </div>
                <div id="footer">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-lg pull-right" style="margin-right: 30px;">
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
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

<script src="__JS__/jquery.dataTables.min.js"></script>
<script src="__JS__/jquery.jedate.js"></script>
<script src="__JS__/dist/plyr.js"></script>

<script type="text/javascript">
    $(".vcontainer input").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",});

    plyr.setup({
        controls: ["duration"]
    });
    $(".mask").click(function () {
        var url = $(this).parent(".container").find("source").attr("src");
        console.log(url);
        url = encodeURIComponent(url);
        window.open("./videoplay.html?url=" + url,"_blank");
    })
    var x = 0;
    $("#edit").click(function () {
        x += 1;
        if(x%2 === 0){
            $("#del").attr("disabled",true);
            $(".icheckbox_square-green").css("display","none");
        }else {
            $("#del").attr("disabled",false);
            $(".icheckbox_square-green").css("display","block");
        }
    })
    $("#del").click(function () {
        $("#content input[type='checkbox']:checked").each(function (i,n) {
            console.log(i,n);
        })
    })
    var uploader = WebUploader.create({
        auto: true,// 选完文件后，是否自动上传。
        swf: '/static/admin/webupload/Uploader.swf',// swf文件路径
        server: "<?php echo url('Upload/uploadVideo'); ?>",// 文件接收服务端。
        chunked: false,
        fileSizeLimit: 1000 *1024 *1024,
        fileNumLimit: 1,
        duplicate :true,// 重复上传图片，true为可重复false为不可重复
        pick: {
            multiple: false,
            id: "#upload"
        },
        accept: {
            title: 'mp4',
            extensions: 'mp4',
            mimeTypes: '.mp4'
        }
    });

    uploader.on( 'fileQueued', function( file ) {
        $("#file_per").append('<div id="' + file.id + '" class="item">' +
            '</div>');
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
            layer.msg(data.msg,{icon:1,time:1500,shade: 0.1});
            $("#file_per").empty();
        }else {
            layer.msg("抱歉，您没有此权限");
        }

    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ,data) {
        layer.msg("上传失败",{icon:2,time:1500,shade: 0.1});
        $("#file_per").empty();
    });
</script>
</body>
</html>