{{layout 'Common/base.tpl'}}
{{fill header}}
<style>
    .module_name {
        width: 98% !important;
        margin-top: 4px;
        font-size: larger;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .layui-btn-disabled {
        color: white;
        font-size: x-large;
        height: 48px;
        line-height: 43px;
        background-color: #5FB878 !important;
    }
</style>
{{end header}}
{{fill container}}

<div class="layui-card">
    <div class="layui-card-header"><h1>{{$cur_project}}</h1></div>
    <div class="layui-card-body">

        <div class="layui-row" style="margin-bottom: 15px">
            {{loop $cur_modules $module}}
                <div class="layui-col-sm2 layui-col-xs4" style="height: 50px;">
                    <div class="grid-demo grid-demo-bg1">
                        <a class="layui-btn layui-btn-normal module_name
                    {{if $module['x_id']==$x_id}}
                    layui-btn-disabled layui-anim layui-anim-scale
                    {{/if}}
                    " href="/request/{{$module['x_id']}}.html"
                           style="background-color: {{$module['color']}}"
                        >{{$module['module']}}</a>
                    </div>
                </div>
            {{/loop}}
        </div>

    </div>
</div>

<div class="layui-row layui-col-space15" style="margin: 0!important;">
    <div class="searchTable">
        <div class="layui-inline" style="width: 190px">
            <input class="layui-input" name="trace_id" id="trace_id" autocomplete="off" placeholder="trace_id">
        </div>
        <div class="layui-inline" style="width: 300px">
            <input class="layui-input" name="url" id="url" autocomplete="off" placeholder="url">
        </div>
        <div class="layui-inline" style="width: 300px">
            <input class="layui-input" type="text" name="request_time" id="request_time" placeholder="请求时间范围" readonly>
            <input type="hidden" name="request_time_input" id="request_time_input" value="">
        </div>
        <div class="layui-inline" style="width: 100px">
            <form class="layui-form" style="margin-bottom: 6px;">
                <input type="checkbox" name="open" id="showHost" lay-skin="switch"
                       lay-text="显示host|隐藏host">
            </form>
        </div>

        <button class="layui-btn" data-type="reload">搜索</button>
        <a style="float: right;
    line-height: 38px;
    cursor: pointer;
    color: #f34242;" id="clearRequest">清空该模块所有请求</a>
    </div>
    <table class="layui-hide" lay-filter='request_list' id="request_list"></table>
</div>

{{end container}}

{{fill tail}}
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">查看</a>
</script>
<script src="/static/jquery.min.js"></script>
<script>
    layui.use(['form', 'laydate'], function () {
        var laydate = layui.laydate;
        //日期时间范围y
        laydate.render({
            elem: '#request_time'
            , type: 'datetime',
            theme: 'molv',
            range: '~'
            , closeStop: '#request_time' //这里代表的意思是：点击 test1 所在元素阻止关闭事件冒泡。如果不设定，则无法弹出控件
            , done: function (value, date, endDate) {
                var $ = layui.$;

                $('#request_time_input').attr('value', value)
                // console.log(this.elem.attr('value',value))
            }
        });
    });
    layui.use('table', function () {
        var table = layui.table;

        table.render({
            elem: '#request_list'
            , url: '/api/request/list',
            where: {
                x_id:{{$x_id}}
            },
            limit: 20
            // , cellMinWidth: 50
            , cols: [[
                // {field: 'record_id', title: 'ID', sort: true}
                {field: 'trace_id', width: 190, title: 'trace_id', fixed: 'left'},
                {field: 'url', title: 'url', sort: true},
                {field: 'request_time', title: '请求时间', width: 180, sort: true},
                {field: 'all_cost_time', width: 150, title: 'sql总耗时(ms)', sort: true},
                {field: 'sql_count', width: 100, title: 'sql数量', sort: true},
                {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 70},
            ]]
            , page: true,
            parseData: function (res) { //res 即为原始返回的数据
                return {
                    "code": res.code, //解析接口状态
                    "msg": res.message, //解析提示文本
                    "count": res.data.count, //解析数据长度
                    "data": res.data.list //解析数据列表
                };
            }
        });

        //监听行工具事件
        table.on('tool(request_list)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                location.href = '/analyze/' + data.l_id + '.html'
            }
        });

        var $ = layui.$, active = {
            reload: function () {
                var traceId = $('#trace_id');
                var url = $('#url');
                var requestTime = $('#request_time_input');
                var showHost = $('#showHost').is(':checked');

                //执行重载
                table.reload('request_list', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    },
                    limit: 20
                    , where: {
                        trace_id: traceId.val(),
                        url: url.val(),
                        request_time: requestTime.val(),
                        show_host: showHost ? 1 : 0
                    }
                }, 'data');
            }
        };

        $('.searchTable .layui-btn').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        $('#clearRequest').on('click', function () {
            layer.confirm('确定要清空吗?', {icon: 3, title: '提示'}, function (index) {
                $.post("/api/request/clear", {
                    x_id: {{$x_id}}
                }, function (res) {
                    if (res.code !== 0) {
                        layer.msg(res.message, {icon: 5});
                    } else {
                        layer.msg('清除完毕');
                        layer.close(index);
                        active['reload'].call(this);
                    }
                });
                layer.close(index);
            });
        })
    });
</script>
{{end tail}}
