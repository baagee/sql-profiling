<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/27
 * Time: 16:28
 */

namespace App\Action\Page;

class ReadmePage extends PageHeaderBase
{
    protected function execute(array $params = [])
    {
        $example = '{
    "project": "MdEditor",
    "module": "admin",
    "trace_id": "157216631577625584",
    "url": "http:\/\/10.188.60.200:8550\/ppppp?rrrr=9090",
    "request_time": 1572166315,
    "sql_profiling": "[{\"Query_ID\":\"1\",\"Duration\":\"0.00116050\",\"Query\":\"show variables where Variable_name=\\\"profiling\\\"\",\"detail\":[{\"Status\":\"starting\",\"Duration\":\"0.000028\"},{\"Status\":\"checking permissions\",\"Duration\":\"0.000008\"},{\"Status\":\"Opening tables\",\"Duration\":\"0.000007\"},{\"Status\":\"init\",\"Duration\":\"0.000022\"},{\"Status\":\"System lock\",\"Duration\":\"0.000006\"},{\"Status\":\"optimizing\",\"Duration\":\"0.000004\"},{\"Status\":\"optimizing\",\"Duration\":\"0.000003\"},{\"Status\":\"statistics\",\"Duration\":\"0.000007\"},{\"Status\":\"preparing\",\"Duration\":\"0.000008\"},{\"Status\":\"statistics\",\"Duration\":\"0.000010\"},{\"Status\":\"preparing\",\"Duration\":\"0.000006\"},{\"Status\":\"executing\",\"Duration\":\"0.000006\"},{\"Status\":\"Sending data\",\"Duration\":\"0.000005\"},{\"Status\":\"executing\",\"Duration\":\"0.000003\"},{\"Status\":\"Sending data\",\"Duration\":\"0.000959\"},{\"Status\":\"end\",\"Duration\":\"0.000008\"},{\"Status\":\"query end\",\"Duration\":\"0.000005\"},{\"Status\":\"closing tables\",\"Duration\":\"0.000004\"},{\"Status\":\"removing tmp table\",\"Duration\":\"0.000026\"},{\"Status\":\"closing tables\",\"Duration\":\"0.000005\"},{\"Status\":\"freeing items\",\"Duration\":\"0.000023\"},{\"Status\":\"cleaning up\",\"Duration\":\"0.000009\"}]}]"
}';
        return [
            'title'          => '使用文档',
            'project_module' => $this->getHeaderMenu(),
            'api'            => 'http://' . $_SERVER['HTTP_HOST'] . '/api/receiver',
            'method'         => 'post',
            'headers'        => [
                "Content-Type: application/json"
            ],
            'params'         => [
                'example' => $example,
                'detail'  => [
                    [
                        'field' => 'project',
                        'doc'   => '项目名字 必填',
                    ],
                    [
                        'field' => 'module',
                        'doc'   => '模块名字 必填',
                    ],
                    [
                        'field' => 'trace_id',
                        'doc'   => '请求id 必填',
                    ],
                    [
                        'field' => 'url',
                        'doc'   => '请求地址 必填',
                    ],
                    [
                        'field' => 'request_time',
                        'doc'   => '请求时间戳 精确到秒 必填',
                    ],
                    [
                        'field' => 'sql_profiling',
                        'doc'   => 'sql执行详情 json字符串 必填',
                    ],
                ]
            ]
        ];
    }
}