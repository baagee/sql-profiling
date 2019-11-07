<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/30
 * Time: 下午12:10
 */

namespace App\Middleware;

use BaAGee\Config\Config;
use BaAGee\Log\Log;
use BaAGee\Template\View;
use BaAGee\NkNkn\AppEnv;
use BaAGee\NkNkn\Base\MiddlewareAbstract;
use BaAGee\NkNkn\Constant\CoreNoticeCode;
use BaAGee\NkNkn\UserNotice;

class ReturnHtml extends MiddlewareAbstract
{
    protected function handler(\Closure $next, $data)
    {
        View::init([
            'sourceViewPath'  => AppEnv::get('APP_PATH') . DIRECTORY_SEPARATOR . 'View',
            'compileViewPath' => AppEnv::get('RUNTIME_PATH') . DIRECTORY_SEPARATOR . 'view_compile',
            'isDebug'         => Config::get('app/is_debug'),
        ]);
        try {
            $return = $next($data);
        } catch (UserNotice $e) {
            $errCode   = $e->getCode();
            $errMsg    = $e->getMessage();
            $errorData = $e->getErrorData();
            Log::warning(sprintf('逻辑异常：[%d] %s', $errCode, $errMsg));
        } catch (\Exception $e) {
            if (Config::get('app/is_debug')) {
                //开发模式抛出系统异常
                throw $e;
            } else {
                $errCode = CoreNoticeCode::DEFAULT_ERROR_CODE;
                $errMsg  = '系统异常~';
                Log::warning(sprintf('系统异常：[%d] %s', $e->getCode(), $e->getMessage()));
            }
        }
        // $return['errorCode']    = $errCode ?? 0;
        // $return['errorMessage'] = $errMsg ?? 0;
        // $return['errorData']    = $errorData ?? '';
        // $return['_controller']  = AppEnv::get('CONTROLLER');
        // $return['_action']      = AppEnv::get('ACTION');
        header('Content-Type: text/html;charset=UTF-8');
        return View::render(sprintf('%s/%s.tpl', AppEnv::get('CONTROLLER'), AppEnv::get('ACTION')), $return);
    }
}
