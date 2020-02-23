{{layout 'Common/base.tpl'}}
{{fill header}}
<link rel="stylesheet" href="/static/highlight/styles/vs.css">
<script src="/static/highlight/highlight.pack.js"></script>
<style>
    .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .sql {
        padding: 0 !important;
    }

    .layui-timeline-title {
        padding-left: 5px !important;
    }

    .layui-timeline-title:hover {
        background-color: #e5f3ff !important;
    }

    .layui-timeline-content {
        padding-left: 20px !important;
    }
</style>
{{end header}}

{{fill container}}

<div>
    <div style="padding: 5px 15px;background-color: white;">
        <h2 style="color: #666;
    font-weight: 500;    word-break: break-all;">
            <a href="{{$analyze['request_detail']['url']}}" target="_blank">{{$analyze['request_detail']['url']}}</a>
        </h2>
    </div>
    <div style="overflow-x: auto; white-space: nowrap;">
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="200">
                <col width="180">
                <col width="140">
                <col width="100">
                <col width="100">
            </colgroup>
            <thead>
            <tr>
                <th>项目</th>
                <th>模块</th>
                <th>trace_id</th>
                <th>请求时间</th>
                <th>sql总耗时(ms)</th>
                <th>sql数量</th>
                <th>分析时间</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <a style="color: #1E9FFF!important;"
                       href="/request/{{$analyze['request_detail']['x_id']}}.html">{{$analyze['request_detail']['project']}}</a>
                </td>
                <td>
                    <a style="color: #1E9FFF!important;"
                       href="/request/{{$analyze['request_detail']['x_id']}}.html">{{$analyze['request_detail']['module']}}</a>
                </td>
                <td>{{$analyze['request_detail']['trace_id']}}</td>
                <td>{{$analyze['request_detail']['request_time']}}</td>
                <td>{{$analyze['request_detail']['all_cost_time']}}</td>
                <td>{{$analyze['request_detail']['sql_count']}}</td>
                <td>{{$analyze['request_detail']['analyze_time']}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="layui-collapse">
    {{loop $analyze['sql_detail_list'] $i $sqlDetail}}
        <div class="layui-colla-item" style="margin-bottom: 5px;">
            <h2 class="layui-colla-title noselect" style="background-color: white;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;">
                <?php
                    $cost=number_format($sqlDetail['cost'],2,'.','');
                ;?>
                <span class="layui-badge"
                      style="background-color: {{$sqlDetail['time_color']}}!important;">{{$cost}}ms</span>
                {{$sqlDetail['sql']}}
            </h2>
            <div class="layui-colla-content
        {{if $i==0}}
        layui-show
        {{/if}}
        ">
                <div class="layui-row layui-col-space15">

                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">SQL语句 &nbsp;
                                {{if !empty($sqlDetail['explain'])}}
                                    <div id="rate_{{$i}}" style="margin-top: -5px;line-height: normal;"></div>
                                {{/if}}
                            </div>
                            <div class="layui-card-body"
                                 style="word-break: break-all;max-height: 100px;overflow-y: auto">
                                <pre><code class="sql">{{$sqlDetail['sql']}}</code></pre>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md4">
                        <div class="layui-card">
                            <div class="layui-card-header">执行过程和耗时</div>
                            <div class="layui-card-body" style="height: 400px;
    overflow-y: auto;">
                                <ul class="layui-timeline">
                                    {{loop $sqlDetail['profile'] $item}}
                                        <li class="layui-timeline-item" style="padding-bottom:0;">
                                            <i class="layui-icon layui-timeline-axis layui-icon-down"
                                               style="color: {{$item['color']}}"></i>
                                            <div class="layui-timeline-content layui-text">
                                                <div class="layui-timeline-title">{{$item['Status']}}
                                                    : {{$item['Duration']}}ms
                                                </div>
                                            </div>
                                        </li>
                                    {{/loop}}
                                    <li class="layui-timeline-item" style="padding-bottom:0">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content layui-text">
                                            <div class="layui-timeline-title">Total: {{$sqlDetail['cost']}}ms</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md8">
                        <div class="layui-card">
                            <div class="layui-card-header">饼状图分析</div>
                            <div class="layui-card-body pie-analyze
                            {{if $i==0}}
                                pie-analyze-first
                            {{/if}}" id="main_{{$sqlDetail['query_id']}}"
                                 style="width: 100%;height:400px;"></div>
                        </div>
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">执行时间轴</div>
                            <div class="layui-card-body line-analyze
                           {{if $i==0}}
                                line-analyze-first
                            {{/if}}
" id="container_{{$sqlDetail['query_id']}}"
                                 style="width:100%;height:180px;margin-top: -45px;padding:10px"></div>
                        </div>
                    </div>
                    {{if !empty($sqlDetail['explain'])}}
                        <div class="layui-col-md12">
                            <div class="layui-card">
                                <div class="layui-card-header">Explain信息</div>
                                <div class="layui-card-body" id="main_{{$sqlDetail['query_id']}}">
                                    <div style="overflow-x: auto; white-space: nowrap;">
                                        <table class="layui-table" lay-size="md">
                                            <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>select_type</th>
                                                <th>table</th>
                                                <th>partitions</th>
                                                <th>type</th>
                                                <th>possible_keys</th>
                                                <th>key</th>
                                                <th>key_len</th>
                                                <th>ref</th>
                                                <th>rows</th>
                                                <th>filtered</th>
                                                <th>Extra</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{loop $sqlDetail['explain'] $itemExp}}
                                                <tr>
                                                    <td>{{$itemExp['id']}}</td>
                                                    <td>{{$itemExp['select_type']}}</td>
                                                    <td>{{$itemExp['table']}}</td>
                                                    <td>{{$itemExp['partitions']}}</td>
                                                    <td>{{$itemExp['type']}}</td>
                                                    <td>{{$itemExp['possible_keys']}}</td>
                                                    <td>{{$itemExp['key']}}</td>
                                                    <td>{{$itemExp['key_len']}}</td>
                                                    <td>{{$itemExp['ref']}}</td>
                                                    <td>{{$itemExp['rows']}}</td>
                                                    <td>{{$itemExp['filtered']}}</td>
                                                    <td>{{$itemExp['Extra']}}</td>
                                                </tr>
                                            {{/loop}}
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="layui-card">
                                        <div class="layui-collapse">
                                            <div class="layui-colla-item">
                                                <h2 class="layui-colla-title noselect">explain信息解读</h2>
                                                <div class="layui-colla-content">
                                                    <div class="layui-card-body" style="max-height: 400px;
    overflow-y: auto;">
                                                        {{loop $sqlDetail['explication'] $key $val}}
                                                        {{if !empty($val)}}
                                                            <fieldset class="layui-elem-field">
                                                                <legend style="font-size: 16px;font-weight: 400;">{{$key}}</legend>
                                                                <div class="layui-field-box">
                                                                    {{if is_string($val)}}
                                                                        {{$val}}
                                                                    {{else}}
                                                                        {{loop $val $v}}
                                                                        {{$v}}
                                                                            <br>
                                                                        {{/loop}}
                                                                    {{/if}}
                                                                </div>
                                                            </fieldset>
                                                        {{/if}}
                                                        {{/loop}}
                                                        <p>
                                                            更详细解读信息请前往 <a
                                                                    href="https://dev.mysql.com/doc/refman/5.7/en/explain-output.html"
                                                                    target="_blank">https://dev.mysql.com/doc/refman/5.7/en/explain-output.html</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{/if}}
                    {{if !empty($sqlDetail['suggestions'])}}
                        <div class="layui-col-md12">
                            <div class="layui-card">
                                <div class="layui-card-header">本SQL优化建议</div>
                                <div class="layui-card-body" id="main_{{$sqlDetail['query_id']}}">
                                    <ul>
                                        {{loop $sqlDetail['suggestions'] $sugg}}
                                            <li><span class="layui-badge-dot"></span> &nbsp;{{$sugg}}</li>
                                        {{/loop}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    {{/if}}

                </div>
            </div>
        </div>
    {{/loop}}
</div>

{{end container}}

{{fill tail}}
<script src="/static/echarts.min.js"></script>

<script type="text/javascript">
    layui.use('layer', function () {
        var layer = layui.layer;
    });

    function showSql(url) {
        layer.open({
            type: 2,
            area: ['70%', '70%'],
            fixed: true, //固定
            maxmin: false,
            scrollbar: false,
            anim: 5,
            shadeClose: true,
            skin: "layui-layer-molv",
            title: "完整SQL",
            content: url
        });
    }

    hljs.initHighlighting();

    var first = document.getElementsByClassName('pie-analyze-first')[0];
    var newW = first.offsetWidth.toString();
    var pieList = document.getElementsByClassName('pie-analyze');
    for (var i = 0; i <= pieList.length - 1; i++) {
        pieList[i].style.width = newW + 'px';
    }

    first = document.getElementsByClassName('line-analyze-first')[0];
    newW = first.offsetWidth.toString();
    var lineList = document.getElementsByClassName('line-analyze');
    for (i = 0; i <= lineList.length - 1; i++) {
        lineList[i].style.width = newW + 'px';
    }
    {{loop $analyze['sql_detail_list'] $i $sqlDetail}}

    layui.use(['rate'], function () {
        var rate = layui.rate;
        rate.render({
            elem: '#rate_{{$i}}'
            , length: 10
            , value: {{$sqlDetail['score']}} //初始值
            , readonly: true
            , half: true
            , text: true
            , setText: function (value) {
                this.span.text(value + "分");
            }
        });
    });
    // 基于准备好的dom，初始化echarts实例
    var myChart_{{$sqlDetail['query_id']}} = echarts.init(document.getElementById('main_{{$sqlDetail['query_id']}}'));

    option = {
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data:  {{$sqlDetail['legend']}}
        },
        color: {{$sqlDetail['colors']}},
        series: [
            {
                name: '执行阶段',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: {{$sqlDetail['pie_data']}},
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart_{{$sqlDetail['query_id']}}.setOption(option);


    var dom = document.getElementById("container_{{$sqlDetail['query_id']}}");
    var myChart_2__{{$sqlDetail['query_id']}} = echarts.init(dom);
    option = null;

    var startTime = 0;

    function renderItem(params, api) {
        var categoryIndex = api.value(0);
        var start = api.coord([api.value(1), categoryIndex]);
        var end = api.coord([api.value(2), categoryIndex]);
        var height = api.size([0, 1])[1] * 0.5;
        var rectShape = echarts.graphic.clipRectByRect({
            x: start[0],
            y: start[1] - height / 2,
            width: end[0] - start[0],
            height: height
        }, {
            x: params.coordSys.x,
            y: params.coordSys.y,
            width: params.coordSys.width,
            height: params.coordSys.height
        });

        return rectShape && {
            type: 'rect',
            shape: rectShape,
            style: api.style()
        };
    }


    option = {
        tooltip: {
            formatter: function (params) {
                return params.marker + params.name + ': ' + params.value[3] + ' us';
            }
        },
        dataZoom: [{
            type: 'slider',
            filterMode: 'weakFilter',
            showDataShadow: false,
            top: 145,
            height: 10,
            borderColor: 'transparent',
            backgroundColor: '#e2e2e2',
            handleIcon: 'M10.7,11.9H9.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4h1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7v-1.2h6.6z M13.3,22H6.7v-1.2h6.6z M13.3,19.6H6.7v-1.2h6.6z', // jshint ignore:line
            handleSize: 15,
            handleStyle: {
                shadowBlur: 6,
                shadowOffsetX: 1,
                shadowOffsetY: 2,
                shadowColor: '#aaa'
            },
            labelFormatter: ''
        }, {
            type: 'inside',
            filterMode: 'weakFilter'
        }],
        grid: {
            height: 50
        },
        xAxis: {
            min: startTime,
            scale: true,
            axisLabel: {
                formatter: function (val) {
                    // console.log(val)
                    return Math.max(0, val - startTime) + ' us';
                }
            }
        },
        yAxis: {
            data: ['执行过程']
        },
        series: [{
            type: 'custom',
            renderItem: renderItem,
            itemStyle: {
                normal: {
                    opacity: 0.8
                }
            },
            encode: {
                x: [1, 2],
                y: 0
            },
            data: {{$sqlDetail['timeline_data']}}
        }]
    };
    if (option && typeof option === "object") {
        myChart_2__{{$sqlDetail['query_id']}}.setOption(option, true);
    }

    {{/loop}}
</script>

{{end tail}}