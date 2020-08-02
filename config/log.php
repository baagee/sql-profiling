<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/5
 * Time: 11:16
 */

use BaAGee\Config\Config;
use BaAGee\Log\Handler\FileLog;
use BaAGee\NkNkn\AppEnv;
use BaAGee\NkNkn\LogFormatter;
use BaAGee\Log\LogLevel;

return [
    'handler'               => FileLog::class,
    'handler_config'        => [
        // 基本目录
        'base_log_path'   => implode(DIRECTORY_SEPARATOR, [AppEnv::get('RUNTIME_PATH'), 'log']),
        // 是否按照小时分割
        'auto_split_hour' => true,
        'sub_dir'        => Config::get('app/app_name'),
    ],
    'cache_limit_percent'   => 5,
    'formatter'             => LogFormatter::class,
    'product_hidden_levels' => [
        LogLevel::INFO, LogLevel::DEBUG
    ],
];