<div class="layui-header header" style="position: fixed;
    top: 0;
    width: 100%;">
    <ul class="layui-nav layui-bg-green">
        <li class="layui-nav-item"><a href="/">SQL性能可视化分析工具</a></li>
        {{loop $project_module['header_menu'] $project $modules}}
            <li class="layui-nav-item">
                <a href="javascript:;">{{$project}}</a>
                <dl class="layui-nav-child">
                    {{loop $modules $module}}
                        <dd><a href="/request/{{$module['x_id']}}">{{$module['module']}}</a></dd>
                    {{/loop}}
                </dl>
            </li>
        {{/loop}}
        <li class="layui-nav-item"><a href="/readme">使用文档</a></li>
    </ul>
</div>
