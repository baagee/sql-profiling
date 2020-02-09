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

        $abstract_code = $this->getAbstractCode();
        $example_code  = $this->getExampleCode();
        $example       = json_encode(json_decode($example), JSON_PRETTY_PRINT);;
        return [
            'title'          => '使用文档',
            'main'           => '利用mysql的profiling工具可以分析得到每条sql语句的执行详情，对每条sql进行explain分析，将执行过程和explain数据发送到此平台来进行可视化展示与分析。在请求开始时开启profiling，请求结束时获取profiling信息，得到每条sql，对每个sql进行explain分析，组织数据发送到此平台，关闭profiling。',
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
            ],
            'abstract_code'  => $abstract_code,
            'example_code'   => $example_code,
        ];
    }

    protected function getAbstractCode()
    {
        return 'abstract class SqlExplainProfilingAbstract
{
    protected const SELECT_PROFILING_SQL   = \'SELECT @@profiling as p_v\';
    protected const OPEN_PROFILING_SQL     = \'SET profiling=1\';
    protected const CLOSE_PROFILING_SQL    = \'SET profiling=0\';
    protected const SHOW_PROFILES_SQL      = \'SHOW PROFILES\';
    protected const SHOW_PROFILE_QUERY_SQL = \'SHOW PROFILE for query \';
    protected const EXPLAIN_SQL            = "EXPLAIN ";

    /**
     * @var null 保存mysql连接
     */
    protected static $connection = null;
    /**
     * @var bool 是否已经注册
     */
    protected static $isRegistered = false;
    /**
     * @var array 保存register传入的参数
     */
    protected static $args = [];

    /**
     * SqlExplainProfilingAbstract constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     * 注册功能
     * @param array $args
     */
    final public static function register($args = [])
    {
        if (static::$isRegistered === false) {
            $self               = new static();
            static::$args       = $args;
            static::$connection = $self->getMySqlConnection();
            if ($self->isOpenProfiling()) {
                //先关闭
                $self->closeProfiling();
            }
            $self->openProfiling();
            if ($self->isOpenProfiling()) {
                register_shutdown_function(function () use ($self) {
                    if (function_exists(\'fastcgi_finish_request\')) {
                        fastcgi_finish_request();
                    }
                    try {
                        $self->collect();
                    } catch (\Throwable $e) {

                    } finally {
                        $self->closeProfiling();
                    }
                });
            }
            static::$isRegistered = true;
        }
    }

    /**
     * 请求结束时搜集sql信息
     */
    final protected function collect()
    {
        $profiles = $this->getProfiles();

        foreach ($profiles as $i => &$profile) {
            if (strpos(strtolower($profile[\'Query\']), \'@@profiling\') !== false) {
                unset($profiles[$i]);
                continue;
            }
            $profileDetail      = $this->getProfileDetail($profile[\'Query_ID\']);
            $profile[\'detail\']  = $profileDetail;
            $profile[\'explain\'] = [];
            $profile[\'Query\']   = $this->getFullSql($profile);
            if (static::isNormalSql($profile[\'Query\'])) {
                //只允许增删改查
                try {
                    $explain = $this->getSqlExplain($profile[\'Query\']);
                } catch (\Throwable $e) {
                    $explain = [];
                } finally {
                    $profile[\'explain\'] = $explain;
                }
            }
        }

        unset($profile);

        if (is_array($profiles) && !empty($profiles)) {
            $ret = $this->beforeRequest($this->getRequestData($profiles));
            static::requestUrl($ret[\'url\'], $ret[\'method\'], $ret[\'data\'], $ret[\'headers\'], $ret[\'is_json\']);
        }
    }

    /**
     * 获取sql之外的其他信息 平台需要的其他信息
     * @param $explainProfiles
     * @return array
     */
    final protected function getRequestData($explainProfiles)
    {
        $project = $this->getProjectName();
        $module  = $this->getModuleName();
        if (PHP_SAPI == \'cli\') {
            $url = $_SERVER[\'PHP_SELF\'] ?? $_SERVER[\'SCRIPT_NAME\'] ?? $_SERVER[\'SCRIPT_FILENAME\'] ?? $_SERVER[\'PATH_TRANSLATED\'] ?? __FILE__;
        } else {
            $url = \'http://\' . $_SERVER[\'HTTP_HOST\'] . $_SERVER[\'REQUEST_URI\'];
        }
        $trace_id      = $this->getTraceId();
        $request_time  = strval($_SERVER[\'REQUEST_TIME\'] ?? time());
        $sql_profiling = json_encode($explainProfiles);
        return compact(\'project\', \'module\', \'trace_id\', \'url\', \'request_time\', \'sql_profiling\');
    }

    /**
     * 是否是增删改查的sql
     * @param $sql
     * @return bool
     */
    final protected static function isNormalSql($sql)
    {
        return stripos($sql, \'REPLACE\') === 0 ||
            stripos($sql, \'INSERT\') === 0 ||
            stripos($sql, \'DELETE\') === 0 ||
            stripos($sql, \'UPDATE\') === 0 ||
            stripos($sql, \'SELECT\') === 0;
    }

    /**
     * 发送http请求
     * @param string $url     请求地址
     * @param string $method  请求方法
     * @param array  $params  请求参数 数组
     * @param array  $headers 请求头
     * @param bool   $json    是否传输json字符串
     * @return bool|string
     */
    protected static function requestUrl(string $url, string $method, $params = [], array $headers = [], $json = true)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        switch (strtoupper($method)) {
            case "GET" :
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                if (is_array($params) && $json) {
                    $params = json_encode($params, JSON_UNESCAPED_UNICODE);
                }
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                //设置提交的信息
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "PUT" :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                break;
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    /**
     * 获取数据库连接
     * @return mixed
     */
    abstract protected function getMySqlConnection();

    /**
     * 是否开启profiling功能
     * @return bool
     */
    abstract protected function isOpenProfiling(): bool;

    /**
     * 开启profiling功能
     * @return mixed
     */
    abstract protected function openProfiling();

    /**
     * 关闭profiling
     * @return mixed
     */
    abstract protected function closeProfiling();

    /**
     * 获取当前请求的profile列表
     * 获取sql profiles
     * @return array
     */
    abstract protected function getProfiles(): array;

    /**
     * 获取profile详情
     * @param int $queryId
     * @return array
     */
    abstract protected function getProfileDetail(int $queryId): array;

    /**
     * 根据完整sql 获取对应的explain信息
     * @param string $fullSql
     * @return array
     */
    abstract protected function getSqlExplain(string $fullSql): array;

    /**
     * 获取项目名
     * @return string
     */
    abstract protected function getProjectName(): string;

    /**
     * 获取模块名
     * @return string
     */
    abstract protected function getModuleName(): string;

    /**
     * 获取当前请求的 请求ID
     * @return string
     */
    abstract protected function getTraceId(): string;

    /**
     * 获取完整的sql信息 没有被截断的，可以执行的完整sql
     * @param array $profile 每个sql的profile信息
     * @return string
     */
    abstract protected function getFullSql(array $profile): string;

    /**
     * 对搜集到的信息做处理
     * @param $requestData
     * @return array 返回请求地址，方法，header... [
     *               \'url\'     => $url,
     *               \'data\'    => $requestData,
     *               \'method\'  => \'POST\',
     *               \'headers\' => [\'Content-Type: application/json\'],
     *               \'is_json\' => true
     *               ]
     */
    abstract protected function beforeRequest($requestData): array;
}
';
    }

    protected function getExampleCode()
    {
        return 'class SqlExplainProfiling extends SqlExplainProfilingAbstract
{
    /**
     * @return mixed|\PDO
     * @throws \Exception
     */
    protected function getMySqlConnection()
    {
        return Connection::getInstance(true);
    }

    /**
     * @return bool
     */
    protected function isOpenProfiling(): bool
    {
        $stmt = self::$connection->query(self::SELECT_PROFILING_SQL);
        $res  = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (isset($res[0][\'p_v\'])) {
            if ($res[0][\'p_v\'] == \'0\') {
                return false;
            }
        }
        return true;
    }

    /**
     * @return mixed|void
     */
    protected function openProfiling()
    {
        self::$connection->exec(self::OPEN_PROFILING_SQL);
    }

    /**
     * @return array
     */
    protected function getProfiles(): array
    {
        $profiles = [];
        $stmt     = self::$connection->query(self::SHOW_PROFILES_SQL);
        if ($stmt) {
            $profiles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return array_values($profiles);
    }

    /**
     * @param int $queryId
     * @return array
     */
    protected function getProfileDetail(int $queryId): array
    {
        return self::$connection->query(self::SHOW_PROFILE_QUERY_SQL . $queryId)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $fullSql
     * @return array
     */
    protected function getSqlExplain(string $fullSql): array
    {
        return self::$connection->query(self::EXPLAIN_SQL . $fullSql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return string
     */
    protected function getModuleName(): string
    {
        return \'admin\';
    }

    /**
     * @return string
     */
    protected function getProjectName(): string
    {
        return Config::get(\'app/app_name\');
    }

    /**
     * @return string
     */
    protected function getTraceId(): string
    {
        return AppEnv::get(\'TRACE_ID\');
    }

    /**
     * @param array $profile
     * @return string
     */
    protected function getFullSql(array $profile): string
    {
        return $profile[\'Query\'];
    }

    /**
     * @param $requestData
     * @return array
     */
    protected function beforeRequest($requestData): array
    {
        return [
            \'url\'     => \'http://127.0.0.1:8580/api/receiver\',
            \'method\'  => \'POST\',
            \'headers\' => [\'Content-Type: application/json\'],
            \'is_json\' => true,
            \'data\'    => $requestData
        ];
    }

    /**
     * @return mixed|void
     */
    protected function closeProfiling()
    {
        self::$connection->exec(self::CLOSE_PROFILING_SQL);
    }
}';
    }
}
