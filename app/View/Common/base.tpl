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
    </style>
    {{hole header}}
</head>
<body>

<div class="layui-container" style="padding: 20px; background-color: #F2F2F2;margin-bottom: 20px;margin-top: 15px;">
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