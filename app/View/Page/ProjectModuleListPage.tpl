{{layout "Common/base.tpl"}}
{{fill header}}
<style>
    .module_name {
        width: 98% !important;
        margin-top: 4px;
        height: 60px;
        line-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: larger;
    }

    .module_name:hover #req-number {
        display: block;
    }

    #req-number {
        font-size: 12px !important;;
        position: absolute !important;;
        top: -15px !important;;
        left: 5px !important;;
        color: #f2f2f2 !important;
        display: none;
    }
</style>
{{end header}}
{{fill container}}

{{if !empty($project_module)}}
    {{loop $project_module $itemLine}}
        <div class="layui-row layui-col-space15">

            {{loop $itemLine $project}}
                <div class="layui-col-md6">
                    <div class="layui-card layui-anim layui-anim-scale">
                        <div class="layui-card-header">
                            <h1 style="display: inline-block">{{$project['project']}}</h1>
                            <i class="layui-icon" style="float: right;font-size: 19px;
    margin-top: 6px;cursor: pointer;color: red" onclick="deleteThis('{{$project['project']}}')">&#xe640;</i>
                        </div>
                        <div class="layui-card-body">

                            {{loop $project['modules'] $modules}}
                                <div class="layui-row" style="margin-top: 5px;margin-bottom: 5px">
                                    {{loop $modules $module}}
                                        <div class="layui-col-xs6 layui-col-sm3">
                                            <div class="grid-demo grid-demo-bg1">
                                                <a class="layui-btn layui-btn-normal module_name"
                                                   href="/request/{{$module['x_id']}}.html"
                                                   style="background-color: {{$module['color']}}">
                                                    <span id="req-number">{{php echo $xid2count[$module['x_id']]??0}}</span>
                                                    {{$module['module']}}</a>
                                            </div>
                                        </div>
                                    {{/loop}}
                                </div>
                            {{/loop}}

                        </div>
                    </div>
                </div>
            {{/loop}}

        </div>
    {{/loop}}
{{else}}
    <div style="text-align: center;margin: 40px;color: #c5c2c2">
        <h1>还没有数据哦</h1>
    </div>
{{/if}}
{{end container}}

{{fill tail}}
<script src="/static/jquery.min.js"></script>
<script>
    layui.use('layer', function () {
        var layer = layui.layer;
    });

    function deleteThis(project) {
        layer.confirm('确定要删除吗?', {icon: 3, title: '提示'}, function (index) {
            $.post("/api/project/delete", {
                project: project
            }, function (res) {
                if (res.code !== 0) {
                    layer.msg(res.message, {icon: 5});
                } else {
                    layer.msg('删除完毕');
                    location.href = '/';
                }
            });
            layer.close(index);
        });
    }
</script>
{{end tail}}