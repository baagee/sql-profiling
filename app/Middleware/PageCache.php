<?php
/**
 * Desc: 进行页面缓存
 * User: baagee
 * Date: 2019/3/30
 * Time: 下午12:10
 */

namespace App\Middleware;

use BaAGee\Config\Config;
use BaAGee\NkNkn\Base\MiddlewareAbstract;

class PageCache extends MiddlewareAbstract
{
    protected function handler(\Closure $next, $data)
    {
        if (Config::get('app/is_debug')) {
            //开发调试模式 不使用页面缓存
            return $next($data);
        }
        $expire = Config::get('app/page_cache_time');
        if (intval(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? 0)) + $expire > $_SERVER['REQUEST_TIME']) {
            http_response_code(304);
            return;
        }
        $resp = $next($data);
        header("Cache-Control: max-age=" . $expire . ',must-revalidate');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expire) . ' GMT');
        return $resp;
    }
}
