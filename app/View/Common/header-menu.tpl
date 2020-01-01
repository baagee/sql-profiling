<div class="layui-header header" style="position: fixed;
    top: 0;
    width: 100%;">
    <ul class="layui-nav layui-bg-green">
        <li class="layui-nav-item"><a href="/"
                                      style="font-size: 18px;font-weight: 400;    padding-right: 0;">SQL性能分析工具</a></li>
        {{loop $project_module['header_menu'] $project $modules}}
            <li class="layui-nav-item">
                <a href="javascript:;">{{$project}}</a>
                <dl class="layui-nav-child">
                    {{loop $modules $module}}
                        <dd><a href="/request/{{$module['x_id']}}.html">{{$module['module']}}</a></dd>
                    {{/loop}}
                </dl>
            </li>
        {{/loop}}
        <li class="layui-nav-item" style="float: right;"><a href="/readme.html">使用文档</a></li>
    </ul>
</div>
