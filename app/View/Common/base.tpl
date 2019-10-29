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
    {{hole header}}
</head>
<body>

{{include 'Common/header-menu.tpl'}}

<div class="layui-container" style="padding: 20px; background-color: #F2F2F2;margin-top: 75px;margin-bottom: 20px">
    {{hole container}}
</div>

<script src="/static//layui/layui.js" charset="utf-8"></script>
<script>
    layui.use('element', function () {
        var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块

        //监听导航点击
        element.on('nav(demo)', function (elem) {
            //console.log(elem)
            layer.msg(elem.text());
        });
    });
    // 回到顶部
    layui.use('util', function(){
        var util = layui.util;
        util.fixbar();
    });
</script>

{{hole tail}}


</body>
</html>