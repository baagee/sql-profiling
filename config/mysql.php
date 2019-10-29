<?php
/**
 * Desc: mysql数据库配置
 * User: baagee
 * Date: 2019/3/15
 * Time: 下午6:47
 */

use BaAGee\NkNkn\AppEnv;

return [
    'host'             => '127.0.0.1',
    'port'             => 5200,
    'user'             => 'ttt',
    'password'         => '1q2w3tyer',
    'database'         => 'sql_profiling',
    'connectTimeout'   => 1,
    'charset'          => 'utf8mb4',
    'schemasCachePath' => AppEnv::get('RUNTIME_PATH') . DIRECTORY_SEPARATOR . 'schemas',
];
