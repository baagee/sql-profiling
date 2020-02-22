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
    <style>
        body {
            opacity: 0;
            animation: page-fade-in 1.2s forwards;
            /*animation: page-scale-up 1s forwards;*/
            background-image: url(/static/bg.png);
            background-repeat: repeat-x;
            background-attachment: fixed;
        }

        @keyframes page-fade-in {
            0% {
                opacity: 0
            }

            100% {
                opacity: 1
            }
        }


        @keyframes page-scale-up {
            0% {
                opacity: 0;
                transform: scale(.9)
            }

            100% {
                opacity: 1;
                transform: scale(1)
            }
        }
        #refreshIcon{
            float: right;
            transition:All 0.4s ease-in-out;
            -webkit-transition:All 0.4s ease-in-out;
            -moz-transition:All 0.4s ease-in-out;
            -o-transition:All 0.4s ease-in-out;
        }
        #refreshIcon:hover{
            transform:rotate(360deg);
            -webkit-transform:rotate(360deg);
            -moz-transform:rotate(360deg);
            -o-transform:rotate(360deg);
            -ms-transform:rotate(360deg);
        }
    </style>
    <script>
        window.onload = function () {
            if (window.top !== window) {
                var thisSrc = document.location.href;
                // console.log('sub:当前iframe.location.href=' + thisSrc)
                var arrUrl = thisSrc.split("//");
                var start = arrUrl[1].indexOf("/");
                if (arrUrl[1].substring(start).indexOf('/sql/') === -1) {
                    // console.log('sub:设置window.parent.location.hash=' + arrUrl[1].substring(start))
                    window.parent.history.replaceState('', document.title, '/#' + arrUrl[1].substring(start));
                }
            }
        }
    </script>
    {{hole header}}
</head>
<body>

<div class="layui-container" style="padding: 20px; background-color: #F2F2F2;margin-bottom: 20px;margin-top: 15px;">
    <div style="padding-bottom: 15px" id="small-menu">
        <span class="layui-breadcrumb">
            {{loop $breadcrumb $itemBc}}
            {{if $itemBc['end']}}
                <a><cite>{{$itemBc['name']}}</cite></a>
            {{else}}
                <a href="{{$itemBc['href']}}">{{$itemBc['name']}}</a>
            {{/if}}
            {{/loop}}
        </span>
        <span id="refreshIcon">
            <a href="" class="layui-icon layui-icon-refresh-3" style="color: #999999;" title="点击刷新页面"></a>
        </span>
    </div>
    {{hole container}}
</div>

<script src="/static/layui/layui.js" charset="utf-8"></script>
<script>
    // 回到顶部
    layui.use(['element', 'util'], function () {
        var util = layui.util;
        util.fixbar();
    });
</script>

{{hole tail}}
</body>
</html>