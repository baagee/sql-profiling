<?php

return [
    'app_name'             => 'SqlProfiling',
    'timezone'             => 'PRC',
    '404file'              => '/static/404.html',//404时页面文件路径
    // 是否开发模式
    'is_debug'             => false,
    'product_error_hidden' => [E_WARNING, E_NOTICE, E_STRICT, E_DEPRECATED],# 非调试模式下隐藏哪种PHP错误类型
    'debug_error_hidden'   => [E_WARNING, E_NOTICE, E_STRICT, E_DEPRECATED],# 调试开发模式下隐藏哪种PHP错误类型
    'page_cache_time'      => 3600 * 24,//页面缓存时间 秒
];
