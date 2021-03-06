<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:44
 */

namespace App\Action\Api;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class ReceiverApi
 * @package App\Action\Api
 */
class ReceiverApi extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        'project'       => ['string|required|min[2]', '项目名称不合法'],
        'module'        => ['string|required|min[2]', '模块名称不合法'],
        'url'           => ['string|required|min[2]', 'url不合法'],
        'trace_id'      => ['string|required|min[2]', '请求ID不合法'],
        'request_time'  => ['integer|required', '请求时间不合法'],
        'sql_profiling' => ['json|decode', 'sql信息不合法'],
    ];

    /**
     * @param array $params
     * @return bool|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        if (empty($params['sql_profiling']) || !is_array($params['sql_profiling'])) {
            return false;
        }
        $dataService = new DataService();
        return $dataService->saveReceive($params['project'], $params['module'], $params['url'], $params['trace_id'], $params['request_time'], $params['sql_profiling']);
    }
}
