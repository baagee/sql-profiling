{{layout 'Common/base.tpl'}}
{{fill header}}
<script src="/static/ace-editor/ace.min.js"></script>
<script src="/static/ace-editor/ext-language_tools.min.js"></script>
<script src="/static/ace-editor/mode-mysql.min.js"></script>
<style>
    .ace_gutter {
        background-color: #e8e6e6 !important;
    }

    .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .layui-timeline-title {
        cursor: pointer;
        max-height: 44px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .layui-timeline-title:hover {
        background-color: #e5f3ff !important;
    }
</style>
{{end header}}

{{fill container}}
<script>
    var box = document.getElementById("small-menu");
    box.remove();
</script>
<div style="padding: 5px 15px;background-color: white;" class="noselect">
    <h2 style="color: #666;cursor: pointer;
    font-weight: 500;word-break: break-all;" onclick="showHidden()">在线工具</h2>
</div>
<hr>
<form id="asdgdfgds" class="layui-form layui-form-pane" style="display: block">
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label"
               style="border: none;font-size: initial;font-weight: 400;background-color: #e8e6e6;">SQL语句</label>
        <div class="layui-input-block">
            <div id="editor" style="height: 150px;min-height:inherit;">SELECT</div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 150px">
                <select name="conf" id="inner_conf" lay-search="">
                    <option value="">选择内置配置</option>
                    {{loop $inner_conf $conf}}
                        <option value="{{$conf}}">{{$conf}}</option>
                    {{/loop}}
                </select>
            </div>
        </div>
        <div class="layui-inline">
            或者手动输入
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 150px;">
                <input type="text" name="host" id="host" placeholder="请输入Host" value="" autocomplete="true"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 110px;">
                <input type="number" name="port" id="port" min="1000" max="65535" placeholder="请输入Port" value=""
                       autocomplete="true"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 140px;">
                <input type="text" name="user" id="user" placeholder="请输入User" autocomplete="true" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 140px;">
                <input type="password" name="password" id="password" placeholder="请输入Password" autocomplete="true"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline" style="width: 130px;">
                <input type="text" name="database" id="database" placeholder="请输入Database" autocomplete="true"
                       class="layui-input" value="">
            </div>
        </div>
        <div class="layui-input-inline" style="width: 60px;float: right;">
            <button class="layui-btn" onsubmit="false" id="submit_analyze">分析</button>
        </div>
    </div>
    <div class="layui-form-item">
    </div>
</form>

<div class="layui-tab" lay-filter="demo" lay-allowclose="true" style="background-color: white;">
    <ul class="layui-tab-title">
        <li class="layui-this" style="width: 112px;font-size: initial!important;" lay-id="11" id="historyList">历史记录</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            {{if empty($history_list)}}
                <p style="text-align: center;color: #757575;">没有历史记录啊</p>
            {{else}}
                <ul class="layui-timeline" id="history_timeline">
                    {{loop $history_list $h}}
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis layui-icon-down"></i>
                            <div class="layui-timeline-content layui-text">
                                <div class="layui-timeline-title" id="{{$h['s_id']}}"
                                     data-type="tabAdd">【{{$h['create_time']}}】
                                    <span class="layui-badge"
                                          style="background-color: {{$h['time_color']}}!important;">{{$h['cost']}}ms</span> {{$h['sql']}}</div>
                            </div>
                        </li>
                    {{/loop}}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                只展示最近的100条记录
                            </div>
                        </div>
                    </li>
                </ul>
            {{/if}}
        </div>

    </div>
</div>
{{end container}}

{{fill tail}}
<script>
    removeFirstClose();

    function showHidden() {
        var div = document.getElementById("asdgdfgds");
        if (div.style.display === 'block') {
            div.style = "display:none";
        } else {
            div.style = "display:block";
        }
    }

    function getHeight() {
        var h = document.documentElement.clientHeight;
        var iframes = document.getElementsByClassName("internal-frame");
        if (iframes.length > 0) {
            for (var i = 0; i < iframes.length; i++) {
                var iframe = iframes[i];
                var topHeight = 60;
                iframe.height = h - topHeight;
            }
        }
    }

    window.onresize = getHeight;

    layui.use(['form', 'element'], function () {
        var form = layui.form,
            $ = layui.jquery,
            element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
        form.render();

        //触发事件
        var active = {
            tabAdd: function (id = 0) {
                var has = false;
                var ll = document.getElementsByClassName('layui-tab-title')[0];
                var lay_id = 0;

                for (var i = 1; i < ll.children.length; i++) {
                    lay_id = ll.children[i].getAttribute('lay-id')
                    if (lay_id == id) {
                        has = true
                        break
                    }
                }
                if (!has) {
                    element.tabAdd('demo', {
                        title: id
                        ,
                        content: '<iframe width="100%" height="100%" onresize="getHeight()" class="internal-frame"' +
                            'onload="getHeight()" src="/online/' + id + '.html" frameborder="0"></iframe>'
                        ,
                        id: id
                    })
                    removeFirstClose()
                }
                element.tabChange('demo', id);
            }
            , tabChange: function (id) {
                //切换到指定Tab项
                element.tabChange('demo', id);
                removeFirstClose();
            }
        };
        //
        element.on('tabDelete', function (data) {
            removeFirstClose()
        });

        $('#history_timeline').on('click', '.layui-timeline-title', function () {
            var othis = $(this), type = othis.data('type');
            active[type] ? active[type].call(this, othis[0].getAttribute('id')) : '';
        });

        $('#submit_analyze').on('click', function () {
            var host = $('#host').val()
            var port = $('#port').val()
            var user = $('#user').val()
            var password = $('#password').val()
            var database = $('#database').val()

            var t = document.getElementById("inner_conf");
            var inner_conf = t.value

            var sql = editor.getValue()
            var params = {
                inner_conf: inner_conf,
                host: host,
                port: port,
                user: user,
                password: password,
                database: database,
                sql: sql
            }
            console.log(params)

            $.post("/api/online/analyze", params, function (res) {
                if (res.code !== 0) {
                    layer.msg(res.message, {icon: 5});
                } else {
                    layer.msg('分析完成');
                    active.tabAdd(res.data.s_id)

                    //前面插入记录
                    $('#history_timeline').prepend('<li class="layui-timeline-item">\n' +
                        '                        <i class="layui-icon layui-timeline-axis"></i>\n' +
                        '                        <div class="layui-timeline-content layui-text">\n' +
                        '                            <div class="layui-timeline-title" id="' + res.data.s_id
                        + '" data-type="tabAdd">【' + res.data.time + '】' +
                        '<span class="layui-badge" style="background-color: ' + res.data.time_color + '!important;">' + res.data.cost + 'ms</span> ' +
                        res.data.sql + '</div></div></li>')
                }
            });
            return false;
        })
    });

    setInterval(removeFirstClose, 50);

    function removeFirstClose() {
        var close = document.getElementById('historyList')
        if (close.children.length > 0) {
            close.children[0].style.display = 'none'
        }
    }

    // 编辑器
    var editor = ace.edit("editor");
    editor.setFontSize(15)
    editor.getSession().setMode("ace/mode/mysql"); // 语言高亮
    //自动换行,设置为off关闭
    editor.setShowPrintMargin(false);
    editor.setOption("wrap", "free");
    //以下部分是设置输入代码提示的
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
    });
    editor.getSession().setUseWrapMode(true);
    editor.selection.getCursor(); //获取光标所在行或列
    editor.session.getLength(); //获取总行数
    editor.getSession().setUseSoftTabs(true);
</script>
{{end tail}}