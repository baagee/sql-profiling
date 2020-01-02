<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/27
 * Time: 19:26
 */

namespace App\Action\Api;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class ClearModuleRequestApi
 * @package App\Action\Api
 */
class ClearModuleRequestApi extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        'x_id' => ['integer|required', "x_id不合法"]
    ];

    /**
     * @param array $params
     * @return bool|mixed
     */
    protected function execute(array $params = [])
    {
        $service = new DataService();
        return $service->clearModuleRequest($params['x_id']);
    }
}
