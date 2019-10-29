{{layout 'Common/base.tpl'}}
{{fill header}}

{{end header}}

{{fill container}}

<div class="layui-card">
    <div class="layui-card-header">
        <h1>使用文档</h1>
    </div>
    <div class="layui-card-body">
        <p>
            利用mysql的profiling工具可以分析得到每条sql语句的执行详情，将执行过程数据发送到此平台来进行可视化展示与分析
        </p>
        <br>
        <p>
            接口地址：{{$api}}
        </p>
        <p>
            请求方法：{{$method}}
        </p>
        <p>
            请求头：<br>
            {{loop $headers $header}}
            {{$header}}
                <br>
            {{/loop}}
        </p>
        <br>
        <table class="layui-table" style="word-break: break-word">
            <thead>
            <tr>
                <th>参数</th>
                <th>说明</th>
            </tr>
            </thead>
            <tbody>
            {{loop $params['detail'] $param}}
                <tr>
                    <td>{{$param['field']}}</td>
                    <td>{{$param['doc']}}</td>
                </tr>
            {{/loop}}
            </tbody>
        </table>

        <p>示例参数</p>

        <pre class="layui-code">{{$params['example']}}</pre>


    </div>
</div>


{{end container}}

{{fill tail}}

{{end tail}}