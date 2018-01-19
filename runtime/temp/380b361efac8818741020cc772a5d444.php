<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"D:\phpStudy\WWW\shuzi\public/../application/quality\view\construction\videoplay.html";i:1516334627;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>plyr.js</title>
    <!-- Styles -->
    <link rel="stylesheet" href="__JS__/dist/plyr.css">
    <!-- Plyr core script -->
    <script src="__JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__JS__/dist/plyr.js"></script>
    <style>
        body{background-color: #262626}
        .m{ margin-left: auto; margin-right: auto; width: 1280px; margin-top: 100px; }

    </style>
</head>

<body>
<div class="m">
    <video controls>
        <source id="video" src="" type="video/mp4">
    </video>
</div>
<script>plyr.setup();</script>
<script>
    var url = window.location.search;
    url = decodeURIComponent(url);
    var reg = /[?&][^?&]+=[^?&]+/g;
    var arr = url.match(reg);
    var kind = arr[0].substring(1).split("=")[1];
//    kind = decodeURI(decodeURI(kind));
    $("#video").attr("src",kind);
    console.log(kind);
</script>
</body>
</html>
