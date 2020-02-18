<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>{{$title}}</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
    <script>
        function getHeight() {
            var h = document.documentElement.clientHeight;
            var iframe = document.getElementById("internal-frame");
            var href = iframe.contentWindow.location.href;
            var topHeight = 60;
            if (href.indexOf("projects.html") != -1) {
                topHeight = 63;
            }
            iframe.height = h - topHeight;
        }

        window.onresize = function () {
            getHeight();

        }
    </script>
    <style>
        .layui-this:after {
            width: 0 !important;
        }

        .layui-nav-child dd.layui-this a {
            background-color: white !important;
            color: #0C0C0C !important;
        }
    </style>
</head>
<body>
<div class="layui-header header" style="position: fixed;
    top: 0;
    width: 100%;">
    <ul class="layui-nav layui-bg-blue">
        <li class="layui-nav-item"><a href="/projects.html"
                                      style="font-size: 18px;font-weight: 400;padding-right: 0;"
                                      target="body-container">SQL性能分析工具</a></li>
        {{loop $project_module['header_menu'] $project $modules}}
            <li class="layui-nav-item">
                <a href="javascript:;">{{$project}}</a>
                <dl class="layui-nav-child">
                    {{loop $modules $module}}
                        <dd><a href="/request/{{$module['x_id']}}.html"
                               target="body-container">{{$module['module']}}</a></dd>
                    {{/loop}}
                </dl>
            </li>
        {{/loop}}
        <li class="layui-nav-item" style="float: right;"><a href="/readme.html" target="body-container">使用文档</a></li>
    </ul>
</div>


<iframe src="/projects.html" frameborder="no" scrolling="auto" onresize="getHeight()"
        onload="getHeight()" id="internal-frame"
        style="width: 100%;padding-top: 60px"
        name="body-container"></iframe>

<script src="/static/layui/layui.js" charset="utf-8"></script>
<script>
    // 回到顶部
    layui.use(['element', 'util'], function () {
        var util = layui.util;
        util.fixbar();
    });
</script>

</body>
</html>
