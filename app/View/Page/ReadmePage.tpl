{{layout 'Common/base.tpl'}}
{{fill header}}

{{end header}}

{{fill container}}

<div class="layui-card">
    <div class="layui-card-header">
        <h2>使用文档</h2>
    </div>
    <div class="layui-card-body">
        <p>
            {{$main}}
        </p>
        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>接口地址</legend>
        </fieldset>
        <p>
            {{$api}}
        </p>
        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>请求方法</legend>
        </fieldset>
        <p>
            {{$method}}
        </p>

        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>请求头</legend>
        </fieldset>
        <p>
            {{loop $headers $header}}
            {{$header}}
                <br>
            {{/loop}}
        </p>

        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>请求参数</legend>
        </fieldset>
        <table class="layui-table" style="word-break: break-word">
            <thead>
            <tr>
                <th>参数</th>
                <th>说明</th>
                <th>类型</th>
                <th>是否必须</th>
            </tr>
            </thead>
            <tbody>
            {{loop $params['detail'] $param}}
                <tr>
                    <td>{{$param['field']}}</td>
                    <td>{{$param['doc']}}</td>
                    <td>{{$param['type']}}</td>
                    <td>{{php echo $param['required']?"是":"否"}}</td>
                </tr>
            {{/loop}}
            </tbody>
        </table>

        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>示例参数</legend>
        </fieldset>

        <pre class="layui-code" lay-title="json">{{$params['example']}}</pre>

        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>信息搜集抽象代码</legend>
        </fieldset>

        <pre class="layui-code" lay-title="php">{{$abstract_code}}</pre>

        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>示例代码</legend>
        </fieldset>
        <p>在请求开始时执行：SqlExplainProfiling::register()</p>

        <pre class="layui-code" lay-title="php">{{$example_code}}</pre>

    </div>
</div>


{{end container}}

{{fill tail}}
<script>
    layui.use('code', function () {
        layui.code();
    });
</script>
{{end tail}}