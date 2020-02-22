{{layout 'Common/base.tpl'}}
{{fill header}}
<link rel="stylesheet" href="/static/highlight/styles/vs.css">
<script src="/static/highlight/highlight.pack.js"></script>
<script src="/static/jquery.min.js"></script>
<style>
    pre {
        position: relative;
        border-radius: 3px;
        border: 1px solid #f2f2f2;
        background: #FFF;
        overflow: hidden;
    }

    code {
        display: block;
        padding: 12px 24px;
        overflow-y: auto;
        font-weight: 300;
        font-family: Menlo, monospace;
        font-size: 0.8em;
    }

    code.has-numbering {
        margin-left: 30px;
    }

    .pre-numbering {
        position: absolute;
        top: -6px;
        left: 0;
        width: 28px;
        padding: 12px 2px 12px 0;
        border-right: 1px solid #f2f2f2;
        border-radius: 3px 0 0 3px;
        background-color: #f2f2f2;
        text-align: right;
        font-family: Menlo, monospace;
        font-size: 0.8em;
        color: #AAA;
    }

    .pre-numbering li {
        line-height: 21px;
    }

    .hljs {
        line-height: 21px;
    }
</style>
<script>
    $(function () {
        $('pre code').each(function () {
            var lines = $(this).text().split('\n').length - 1;
            var $numbering = $('<ul/>').addClass('pre-numbering');
            $(this)
                .addClass('has-numbering')
                .parent()
                .append($numbering);
            for (i = 1; i <= lines; i++) {
                $numbering.append($('<li/>').text(i));
            }
        });
    });
</script>
{{end header}}

{{fill container}}
<script>
    var box = document.getElementById("small-menu");
    box.remove();
</script>
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
        <div style="overflow-x: auto; white-space: nowrap;">
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
        </div>
        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>示例参数</legend>
        </fieldset>

        <pre><code class="json">{{$params['example']}}</code></pre>

        <br>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>数据收集示例代码</legend>
        </fieldset>

        <div class="layui-collapse">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">SqlExplainProfilingAbstract.php</h2>
                <div class="layui-colla-content" style="padding: 0;">
                    <pre><code class="php">{{$abstract_code}}</code></pre>
                </div>
            </div>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">MySqlExplainProfiling.php</h2>
                <div class="layui-colla-content" style="padding: 0">
                    <pre><code class="php">{{$example_code}}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>


{{end container}}

{{fill tail}}
<script>
    hljs.initHighlighting();
</script>
{{end tail}}