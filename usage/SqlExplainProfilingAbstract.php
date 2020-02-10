<?php
/**
 * Desc:
 * User: baagee
 * Date: 2020/2/9
 * Time: 下午12:52
 */

namespace Sf;

/**
 * Class SqlExplainProfilingAbstract
 * @package Sf
 */
abstract class SqlExplainProfilingAbstract
{
    protected const SELECT_PROFILING_SQL   = 'SELECT @@profiling AS p_v';
    protected const OPEN_PROFILING_SQL     = 'SET profiling=1';
    protected const SET_PROFILING_SIZE_SQL = 'SET profiling_history_size=100';
    protected const CLOSE_PROFILING_SQL    = 'SET profiling=0';
    protected const SHOW_PROFILES_SQL      = 'SHOW PROFILES';
    protected const SHOW_PROFILE_QUERY_SQL = 'SHOW PROFILE FOR QUERY ';
    protected const EXPLAIN_SQL            = 'EXPLAIN ';

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
            if (!$self->isOpenProfiling()) {
                $self->openProfiling();
            }
            if ($self->isOpenProfiling()) {
                $self->setProfilingSize();
                register_shutdown_function(function () use ($self) {
                    if (function_exists('fastcgi_finish_request')) {
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
            if (strpos(strtolower($profile['Query']), '@@profiling') !== false) {
                unset($profiles[$i]);
                continue;
            }
            if (strpos(strtolower($profile['Query']), 'profiling_history_size') !== false) {
                unset($profiles[$i]);
                continue;
            }

            $profileDetail      = $this->getProfileDetail($profile['Query_ID']);
            $profile['detail']  = $profileDetail;
            $profile['explain'] = [];
            $profile['Query']   = $this->getFullSql($profile);
            if (static::isNormalSql($profile['Query'])) {
                //只允许增删改查
                try {
                    $explain = $this->getSqlExplain($profile['Query']);
                } catch (\Throwable $e) {
                    $explain = [];
                } finally {
                    $profile['explain'] = $explain;
                }
            }
        }

        unset($profile);

        if (is_array($profiles) && !empty($profiles)) {
            $ret = $this->beforeRequest($this->getRequestData($profiles));
            static::requestUrl($ret['url'], $ret['method'], $ret['data'], $ret['headers'], $ret['is_json']);
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
        if (PHP_SAPI == 'cli') {
            $url = $_SERVER['PHP_SELF'] ?? $_SERVER['SCRIPT_NAME'] ?? $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['PATH_TRANSLATED'] ?? __FILE__;
        } else {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        $trace_id      = $this->getTraceId();
        $request_time  = strval($_SERVER['REQUEST_TIME'] ?? time());
        $sql_profiling = json_encode($explainProfiles);
        return compact('project', 'module', 'trace_id', 'url', 'request_time', 'sql_profiling');
    }

    /**
     * 是否是增删改查的sql
     * @param $sql
     * @return bool
     */
    final protected static function isNormalSql($sql)
    {
        return stripos($sql, 'REPLACE') === 0 ||
            stripos($sql, 'INSERT') === 0 ||
            stripos($sql, 'DELETE') === 0 ||
            stripos($sql, 'UPDATE') === 0 ||
            stripos($sql, 'SELECT') === 0;
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
     *               'url'     => $url,
     *               'data'    => $requestData,
     *               'method'  => 'POST',
     *               'headers' => ['Content-Type: application/json'],
     *               'is_json' => true
     *               ]
     */
    abstract protected function beforeRequest($requestData): array;

    /**
     * 设置profiling保存记录数
     * @return mixed
     */
    abstract protected function setProfilingSize();
}
