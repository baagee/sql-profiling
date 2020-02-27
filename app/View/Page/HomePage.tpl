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
            if (href.indexOf("projects.html") !== -1) {
                topHeight = 63;
            }
            // console.log(topHeight)
            iframe.height = h - topHeight;
        }

        window.onresize = getHeight;

        window.onload = function () {
            if (window.top === window) {
                var hash = window.location.hash;
                // console.log('当前hash=' + hash)
                var path = '';
                if (hash === "") {
                    window.location.hash = "#/projects.html"
                    path = '/projects.html';
                    // console.log("hash 为空，设置成projects.html")
                } else {
                    path = hash.substr(1, hash.length - 1);
                    var curPath = document.getElementById("internal-frame").src;
                    // console.log('当前iframe.src=' + curPath)
                    var arrUrl = curPath.split("//");
                    var oldPath = arrUrl[1].substring(arrUrl[1].indexOf("/"));

                    if (oldPath === path) {
                        return false;
                    }
                }
                // console.log('设置iframe.src=' + path)
                document.getElementById("internal-frame").src = path
            }
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
<div class="layui-row">
    <div class="layui-col-sm12 layui-hide-xs">
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
                <li class="layui-nav-item" style="float: right;"><a href="/readme.html" target="body-container">使用文档</a>
                </li>
            </ul>
        </div>


        <iframe src="" frameborder="no" scrolling="auto" onresize="getHeight()"
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
    </div>
    <div class="layui-col-xs12 layui-hide-sm">
        <div style="text-align: center;
    margin-top: 67%;
    color: #e24040;">
            <h1>屏幕太小啦，展示不出来啦</h1>
        </div>
    </div>
</div>
</body>
</html>
