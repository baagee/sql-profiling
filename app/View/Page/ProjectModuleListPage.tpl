{{layout "Common/base.tpl"}}
{{fill container}}

{{loop $project_module['list'] $itemLine}}
    <div class="layui-row layui-col-space15">

        {{loop $itemLine $project}}
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header"><h1>{{$project['project']}}</h1></div>
                    <div class="layui-card-body">

                        {{loop $project['modules'] $modules}}
                            <div class="layui-row" style="margin-top: 5px;margin-bottom: 5px">
                                {{loop $modules $module}}
                                    <div class="layui-col-xs3">
                                        <div class="grid-demo grid-demo-bg1">
                                            <a class="layui-btn layui-btn-normal"
                                               href="/request/{{$module['x_id']}}">{{$module['module']}}</a>
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

{{end container}}