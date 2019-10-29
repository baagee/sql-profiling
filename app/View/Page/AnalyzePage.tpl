{{layout 'Common/base.tpl'}}
{{fill header}}
<link rel="stylesheet" href="/static/highlight/styles/vs.css">
<script src="/static/highlight/highlight.pack.js"></script>
<style>
    .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Chrome/Safari/Opera */
        -khtml-user-select: none; /* Konqueror */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Non-prefixed version, currentlynot supported by any browser */
    }
</style>
{{end header}}

{{fill container}}

<table class="layui-table" style="word-break: break-word">
    <colgroup>
        <col width="150">
        <col width="150">
        <col width="200">
        <col/>
        <col width="140">
        <col width="140">
        <col width="100">
    </colgroup>
    <thead>
    <tr>
        <th>项目</th>
        <th>模块</th>
        <th>trace_id</th>
        <th>url</th>
        <th>请求时间</th>
        <th>sql总耗时(ms)</th>
        <th>sql数量</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <a style="color: #1E9FFF!important;"
               href="/request/{{$analyze['request_detail']['x_id']}}">{{$analyze['request_detail']['project']}}</a>
        </td>
        <td>
            <a style="color: #1E9FFF!important;"
               href="/request/{{$analyze['request_detail']['x_id']}}">{{$analyze['request_detail']['module']}}</a>
        </td>
        <td>{{$analyze['request_detail']['trace_id']}}</td>
        <td>{{$analyze['request_detail']['url']}}</td>
        <td>{{$analyze['request_detail']['request_time']}}</td>
        <td>{{$analyze['request_detail']['all_cost_time']}}</td>
        <td>{{$analyze['request_detail']['sql_count']}}</td>
    </tr>
    </tbody>
</table>

<div class="layui-collapse">
    {{loop $analyze['sql_detail_list'] $i $sqlDetail}}
        <div class="layui-colla-item">
            <h2 class="layui-colla-title noselect" style="background-color: white">
                {{$sqlDetail['sql']}}
            </h2>
            <div class="layui-colla-content
        {{if $i==0}}
        layui-show
        {{/if}}
        ">
                <div class="layui-row layui-col-space15">

                    <div class="layui-col-md4">
                        <div class="layui-card">
                            <div class="layui-card-header">SQL语句</div>
                            <div class="layui-card-body" style="word-break: break-all;">
                                <pre><code class="sql">{{$sqlDetail['sql']}}</code></pre>
                            </div>
                        </div>

                        <div class="layui-card">
                            <div class="layui-card-header">执行过程和耗时</div>
                            <div class="layui-card-body">
                                <ul class="layui-timeline">
                                    {{loop $sqlDetail['detail'] $item}}
                                        <li class="layui-timeline-item" style="padding-bottom:0">
                                            <i class="layui-icon layui-timeline-axis layui-icon-down"></i>
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
                            <div class="layui-card-body" id="main_{{$sqlDetail['query_id']}}"
                                 style="width: 700px;height:400px;"></div>
                        </div>

                        <div class="layui-card">
                            <div class="layui-card-header">执行时间轴</div>
                            <div class="layui-card-body" id="container_{{$sqlDetail['query_id']}}"
                                 style="width: 700px;height:200px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{/loop}}
</div>

{{end container}}

{{fill tail}}
<script type="text/javascript">
    hljs.initHighlighting();
</script>
<script src="/static//echarts.min.js"></script>

<script type="text/javascript">
    {{loop $analyze['sql_detail_list'] $sqlDetail}}
    // 基于准备好的dom，初始化echarts实例
    var myChart_{{$sqlDetail['query_id']}} = echarts.init(document.getElementById('main_{{$sqlDetail['query_id']}}'));

    option = {
        title: {
            text: 'MySQL语句执行各个阶段耗时',
            x: 'right'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data:  {{$sqlDetail['legend']}}
        },
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

    var data = {{$sqlDetail['timeline_data']}};
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
        title: {
            text: 'MySQL语句执行各个阶段时间轴',
            left: 'right',
        },
        dataZoom: [{
            type: 'slider',
            filterMode: 'weakFilter',
            showDataShadow: false,
            top: 160,
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
            height: 60
        },
        xAxis: {
            min: startTime,
            scale: true,
            axisLabel: {
                formatter: function (val) {
                    console.log(val)
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
            data: data
        }]
    };
    if (option && typeof option === "object") {
        myChart_2__{{$sqlDetail['query_id']}}.setOption(option, true);
    }

    {{/loop}}

</script>

{{end tail}}