<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/30
 * Time: 上午12:32
 */

namespace App\Middleware;

use BaAGee\Log\Log;
use BaAGee\NkNkn\Base\MiddlewareAbstract;
use BaAGee\NkNkn\Constant\CoreNoticeCode;
use BaAGee\NkNkn\UserNotice;

class ReturnJson extends MiddlewareAbstract
{
    protected function handler(\Closure $next, $data)
    {
        try {
            $res = $next($data);
        } catch (UserNotice $e) {
            $errMsg  = $e->getMessage();
            $errCode = $e->getCode();
            $res     = $e->getErrorData() ?? '';
            Log::warning(sprintf('逻辑异常：[%d] %s', $errCode, $errMsg));
        } catch (\Exception $e) {
            $errCode = CoreNoticeCode::DEFAULT_ERROR_CODE;
            $errMsg  = '系统异常~';
            Log::warning(sprintf('系统异常：[%d] %s', $e->getCode(), $e->getMessage()));
        }
        header('Content-Type: application/json; charset=utf-8');
        return json_encode([
            'code'    => $errCode ?? 0,
            'message' => $errMsg ?? '',
            'data'    => $res ?? '',
        ], JSON_UNESCAPED_UNICODE);
    }
}
