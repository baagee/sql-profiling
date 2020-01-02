<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/27
 * Time: 16:28
 */

namespace App\Action\Page;

/**
 * Class ReadmePage
 * @package App\Action\Page
 */
class ReadmePage extends PageHeaderBase
{
    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $example = <<<EXP
{"project":"project","module":"manage","trace_id":"548159684457749458","url":"http:\/\/xxx.com:8959\/api\/manage\/area\/arealist?area_name=&note_code=&cur_page=1&per_page=20&is_dynamic=0","request_time":1577338028,"sql_profiling":"[{\"Query_ID\":\"1\",\"Duration\":\"0.00098300\",\"Query\":\"SELECT * FROM area WHERE is_dynamic = 0 ORDER BY create_time desc LIMIT 0, 20\",\"detail\":[{\"Status\":\"starting\",\"Duration\":\"0.000051\"},{\"Status\":\"checking permissions\",\"Duration\":\"0.000017\"},{\"Status\":\"Opening tables\",\"Duration\":\"0.000216\"},{\"Status\":\"init\",\"Duration\":\"0.000035\"},{\"Status\":\"System lock\",\"Duration\":\"0.000016\"},{\"Status\":\"optimizing\",\"Duration\":\"0.000021\"},{\"Status\":\"statistics\",\"Duration\":\"0.000025\"},{\"Status\":\"preparing\",\"Duration\":\"0.000018\"},{\"Status\":\"Sorting result\",\"Duration\":\"0.000013\"},{\"Status\":\"executing\",\"Duration\":\"0.000012\"},{\"Status\":\"Sending data\",\"Duration\":\"0.000021\"},{\"Status\":\"Creating sort index\",\"Duration\":\"0.000429\"},{\"Status\":\"end\",\"Duration\":\"0.000013\"},{\"Status\":\"query end\",\"Duration\":\"0.000013\"},{\"Status\":\"closing tables\",\"Duration\":\"0.000017\"},{\"Status\":\"freeing items\",\"Duration\":\"0.000048\"},{\"Status\":\"cleaning up\",\"Duration\":\"0.000018\"}],\"explain\":[{\"id\":\"1\",\"select_type\":\"SIMPLE\",\"table\":\"area\",\"partitions\":null,\"type\":\"ALL\",\"possible_keys\":null,\"key\":null,\"key_len\":null,\"ref\":null,\"rows\":\"117\",\"filtered\":\"10.00\",\"Extra\":\"Using where; Using filesort\"}]},{\"Query_ID\":\"2\",\"Duration\":\"0.00031475\",\"Query\":\"SELECT COUNT(*) FROM area WHERE is_dynamic = 0\",\"detail\":[{\"Status\":\"starting\",\"Duration\":\"0.000034\"},{\"Status\":\"checking permissions\",\"Duration\":\"0.000014\"},{\"Status\":\"Opening tables\",\"Duration\":\"0.000016\"},{\"Status\":\"init\",\"Duration\":\"0.000019\"},{\"Status\":\"System lock\",\"Duration\":\"0.000014\"},{\"Status\":\"optimizing\",\"Duration\":\"0.000016\"},{\"Status\":\"statistics\",\"Duration\":\"0.000017\"},{\"Status\":\"preparing\",\"Duration\":\"0.000017\"},{\"Status\":\"executing\",\"Duration\":\"0.000012\"},{\"Status\":\"Sending data\",\"Duration\":\"0.000067\"},{\"Status\":\"end\",\"Duration\":\"0.000013\"},{\"Status\":\"query end\",\"Duration\":\"0.000014\"},{\"Status\":\"closing tables\",\"Duration\":\"0.000014\"},{\"Status\":\"freeing items\",\"Duration\":\"0.000035\"},{\"Status\":\"cleaning up\",\"Duration\":\"0.000017\"}],\"explain\":[{\"id\":\"1\",\"select_type\":\"SIMPLE\",\"table\":\"area\",\"partitions\":null,\"type\":\"ALL\",\"possible_keys\":null,\"key\":null,\"key_len\":null,\"ref\":null,\"rows\":\"117\",\"filtered\":\"10.00\",\"Extra\":\"Using where\"}]}]"}
EXP;
        $example = json_encode(json_decode($example), JSON_PRETTY_PRINT);;
        return [
            'title'          => '使用文档',
            'main'           => '利用mysql的profiling工具可以分析得到每条sql语句的执行详情，对每条sql进行explain分析，将执行过程和explain数据发送到此平台来进行可视化展示与分析',
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
                        'field'    => 'project',
                        'doc'      => '项目名字',
                        'type'     => 'string',
                        'required' => true,
                    ],
                    [
                        'field'    => 'module',
                        'doc'      => '模块名字',
                        'required' => true,
                        'type'     => 'string',
                    ],
                    [
                        'field'    => 'trace_id',
                        'doc'      => '请求id',
                        'required' => true,
                        'type'     => 'string',
                    ],
                    [
                        'field'    => 'url',
                        'doc'      => '请求地址',
                        'required' => true,
                        'type'     => 'string',
                    ],
                    [
                        'field'    => 'request_time',
                        'doc'      => '请求时间戳 精确到秒',
                        'required' => true,
                        'type'     => 'int',
                    ],
                    [
                        'field'    => 'sql_profiling',
                        'doc'      => 'sql执行详情, 包括sql语句和执行时间, profiling信息, explain信息。 json字符串',
                        'required' => true,
                        'type'     => 'string',
                    ],
                ]
            ]
        ];
    }
}