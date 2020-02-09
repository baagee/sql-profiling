<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 15:49
 */

namespace Sf;

use Sftcwl\Log\Log;
use Sftcwl\Orm\Connection;
use Sftcwl\Orm\DB;

/**
 * Class MySqlExplainProfiling
 * @package Sf
 */
class MySqlExplainProfiling extends SqlExplainProfilingAbstract
{
    /**
     * @return mixed|Connection
     */
    protected function getMySqlConnection()
    {
        return Connection::getConnection('srm', true);
    }

    /**
     * @return bool
     */
    protected function isOpenProfiling(): bool
    {
        $res = self::$connection->query(self::SELECT_PROFILING_SQL);
        if (isset($res[0]['p_v'])) {
            if ($res[0]['p_v'] == '0') {
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
        self::$connection->query(self::OPEN_PROFILING_SQL);
    }

    /**
     * @return mixed|void
     */
    protected function closeProfiling()
    {
        self::$connection->query(self::CLOSE_PROFILING_SQL);
    }

    /**
     * @return array
     */
    protected function getProfiles(): array
    {
        $ret = self::$connection->query(self::SHOW_PROFILES_SQL);
        return (array)$ret;
    }

    /**
     * @param int $queryId
     * @return array
     */
    protected function getProfileDetail(int $queryId): array
    {
        $ret = self::$connection->query(self::SHOW_PROFILE_QUERY_SQL . strval($queryId));
        return (array)$ret;
    }

    /**
     * @param string $fullSql
     * @return array
     */
    protected function getSqlExplain(string $fullSql): array
    {
        $ret = self::$connection->query(self::EXPLAIN_SQL . $fullSql);
        return (array)$ret;
    }

    /**
     * @return string
     */
    protected function getProjectName(): string
    {
        return strval(array_pop(explode(DIRECTORY_SEPARATOR, ROOT_PATH)));
    }

    /**
     * @return string
     */
    protected function getModuleName(): string
    {
        return strval(AppEnv::getCurrApp());
    }

    /**
     * @return string
     */
    protected function getTraceId(): string
    {
        return strval(Log::genLogID());
    }

    /**
     * @param array $profile
     * @return string
     */
    protected function getFullSql(array $profile): string
    {
        $allSqls = DB::getSqls();
        return trim($allSqls[$profile['Query_ID'] + 1] ?? $profile['Query']);//获取真实的sql 没截断的
    }

    /**
     * @param $requestData
     * @return array
     */
    protected function beforeRequest($requestData): array
    {
        $url = 'http://127.0.0.1:8580/api/receiver';
        return [
            'url'     => $url,
            'data'    => $requestData,
            'method'  => 'POST',
            'headers' => ['Content-Type: application/json'],
            'is_json' => true
        ];
    }
}

/*
 * 在请求开始时执行：MySqlExplainProfiling::register()
 * */
