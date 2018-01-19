<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:74:"D:\phpStudy\WWW\shuzi\public/../application/admin\view\contract\index.html";i:1516331300;s:73:"D:\phpStudy\WWW\shuzi\public/../application/admin\view\public\header.html";i:1516331300;s:73:"D:\phpStudy\WWW\shuzi\public/../application/admin\view\public\footer.html";i:1516331300;}*/ ?>
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
<link rel="stylesheet" href="/static/admin/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="/static/admin/css/jedate.css">
<style>
    .mybtn{
        margin-right: 30px;
        float: left;
    }
    #mytable1_wrapper th,#mytable1_wrapper td{
        text-align: center;
    }
    #myModal input,#myModal select,#myModal textarea{
        background-color: transparent;
    }
</style>
<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div id="mytable1_wrapper">
                    <table id="admin_table" width="100%" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>合同名称</th>
                                <th>合同序号</th>
                                <th>标段名称</th>
                                <th>项目名称</th>
                                <th>签订日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal animated fadeInRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 60%;margin: 0 auto;">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5></h5>
                </div>
                <div class="ibox-content">
                    <!--新增用户信息开始-->
                    <form class="form-horizontal" name="contractAdd" id="contractAdd" method="post" action="<?php echo url('contractAdd'); ?>">
                        <input type="text" id="id" name="id" value="" style="display: none;">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">合同名称：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="hetong_name" type="text" class="form-control" name="hetong_name" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">合同编号：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="sn" type="text" class="form-control" name="sn" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">标段名称：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="biaoduan_name" type="text" class="form-control" name="biaoduan_name" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">标段编号：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="biaoduan_sn" type="text" class="form-control" name="biaoduan_sn" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">项目名称：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input id="xiangmu_name" type="text" class="form-control" name="xiangmu_name" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">甲方：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <select name="first_party" id="first_party" class="form-control" >
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">乙方：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <select name="second_party" id="second_party" class="form-control" >
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="col-lg-6 col-xs-12">
                                    <label class="col-xs-6 col-sm-4 control-label col-sm-offset-2 col-xs-offset-0">签订日期：</label>
                                    <div class="myblock col-xs-6 input-group">
                                        <input type="text" id="qianding_date" class="form-control datepicker" name="qianding_date" placeholder="请选择日期" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <label class="col-xs-6 col-sm-4 control-label">开工日期：</label>
                                    <div class="myblock col-xs-6 input-group">
                                        <input type="text" id="kaigong_date" class="form-control datepicker" name="kaigong_date" placeholder="请选择日期" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="col-lg-6 col-xs-12">
                                    <label class="col-xs-6 col-sm-4 control-label col-sm-offset-2 col-xs-offset-0">合同工期(月)：</label>
                                    <div class="myblock col-xs-6 input-group">
                                        <input name="contract_time" id="contract_time" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <label class="col-xs-6 col-sm-4 control-label">质保期(月)：</label>
                                    <div class="myblock col-xs-6 input-group">
                                        <input name="warranty_time" id="warranty_time" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="col-xs-3 control-label">合同金额(万元)：</label>
                                <div class="myblock col-xs-8 input-group">
                                    <input name="money" id="money" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-offset-6">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 返回</a>
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


</body>
<script src="__JS__/jquery.dataTables.min.js"></script>
<script src="__JS__/jquery.jedate.js"></script>
<script>
$("#qianding_date").jeDate({
    format: "YYYY-MM-DD"
});
$("#kaigong_date").jeDate({
    format: "YYYY-MM-DD"
});
//删除
function myconfirm(id,url) {
    layer.confirm('确认删除此条记录吗?', {icon: 3, title:'提示'}, function(index){
        $.ajax({
            url: url,
            async: false,
            data: {'id' : id},
            type: "get",
            dataType: "json",
            success: function (res) {
                if(res.code == 1){
                    layer.msg(res.msg,{icon:1,time:1500,shade: 0.1});
                    admin_table.ajax.url("__SCRIPT__/hetong_table.php").load();
                }else{
                    layer.msg(res.msg,{icon:0,time:1500,shade: 0.1});
                }
            }
        })
        layer.close(index);
    })
}
function del(that) {
    var uid = $(that).parent("td").parent("tr").children("td:first-child").text();
    myconfirm(uid,'<?php echo url("contractDel"); ?>');
}
//编辑
function edit(that) {
    var hid = $(that).parent("td").parent("tr").children("td:first-child").text();
    $("#myModal h5").text("编辑合同信息");
    $("#id").val(hid);
    $("#myModal").modal('show');
    $.ajax({
        url: "./contractEdit",
        type: "post",
        data: {id:hid},
        dataType: "json",
        success: function (res) {
            console.log(res);
            if(res.msg == "success"){
                $("#myModal select").empty();
                var data = res.data;
                $("#biaoduan_name").val(data.biaoduan_name);
                $("#biaoduan_sn").val(data.biaoduan_sn);
                $("#contract_time").val(data.contract_time);
                $("#hetong_name").val(data.hetong_name);
                $("#kaigong_date\n").val(data.kaigong_date);
                $("#money").val(data.money);
                $("#qianding_date").val(data.qianding_date);
                $("#sn").val(data.sn);
                $("#warranty_time").val(data.warranty_time);
                $("#xiangmu_name").val(data.xiangmu_name);
                $.each(res.first,function (i,n) {
                    var str = "<option value="+ n.name +">"+ n.name +"</option>"
                    $("#first_party").append(str);
                });
                $.each(res.second,function (i,n) {
                    var str = "<option value="+ n.name +">"+ n.name +"</option>"
                    $("#second_party").append(str);
                });
                $("#first_party").val(data.first_party);
                $("#second_party").val(data.second_party);
            }
        }
    })
}
var admin_table = $('#admin_table').DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "__SCRIPT__/hetong_table.php"
    },
    "dom": '<"mybtn btn btn-outline btn-primary"><"myl"l>frtip',
    "columnDefs": [
        {
            "searchable": false,
            "orderable": false,
            "targets": [6],
            "render" :  function(data,type,row) {
                var html = "<input type='button' class='btn btn-primary btn-outline btn-xs' style='margin-left: 5px;' onclick='edit(this)' value='编辑'/>";
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
$("div.mybtn").click(function () {
        $("#myModal h5").text("添加合同信息");
        $("#myModal input").val("");
        $("#myModal select").empty();
        $("#myModal").modal('show');
        $.ajax({
            url: "./contractAddUI",
            type: "post",
            dataType: "json",
            success: function (res) {
                console.log(res);
                if(res.msg == "success"){
                    $.each(res.first,function (i,n) {
                        var str = "<option value="+ n.name +">"+ n.name +"</option>"
                        $("#first_party").append(str);
                    });
                    $.each(res.second,function (i,n) {
                        var str = "<option value="+ n.name +">"+ n.name +"</option>"
                        $("#second_party").append(str);
                    });
                }
            }
        })
})
//提交
$(function(){
    $('#contractAdd').ajaxForm({
//        beforeSubmit: checkForm,
        success: complete,
        dataType: 'json'
    });

//    function checkForm(){
//        if( '' == $.trim($('#username').val())){
//            layer.msg('请输入用户名',{icon:2,time:1500,shade: 0.1}, function(index){
//                layer.close(index);
//            });
//            return false;
//        }
//        var txt1 = $("#myModal h5").text();
//        if(txt1.search(/编辑用户/) < 0){
//            if( '' == $.trim($('#password').val())){
//                layer.msg('请输入登录密码',{icon:2,time:1500,shade: 0.1}, function(index){
//                    layer.close(index);
//                });
//                return false;
//            }
//        }
//
//        if( '' == $.trim($('#real_name').val())){
//            layer.msg('请输入真实姓名',{icon:2,time:1500,shade: 0.1}, function(index){
//                layer.close(index);
//            });
//            return false;
//        }
//    }


    function complete(data){
        console.log($("#action").val())
        console.log(data);
        if(data.code==1){
            $("#myModal").modal('hide');
            layer.msg(data.msg);
            admin_table.ajax.url("__SCRIPT__/hetong_table.php").load();
        }else{
            layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
            return false;
        }
    }

});
</script>
</html>