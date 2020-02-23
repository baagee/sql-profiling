<?php
/**
 * Desc: mysql数据库配置
 * User: baagee
 * Date: 2019/3/15
 * Time: 下午6:47
 */

return [
    'host'           => '127.0.0.1',
    'port'           => 5200,
    'user'           => 'ttt',
    'password'       => '1q2w3tyer',
    'database'       => 'sql_profiling',
    'connectTimeout' => 1,
    'charset'        => 'utf8mb4',
    'options'        => [
        //pdo连接时额外选项
        \PDO::ATTR_PERSISTENT => true,
    ],
];
