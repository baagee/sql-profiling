<?php
/**
 * Desc:
 * User: 01372412
 * Date: 2019/11/9
 * Time: 下午9:38
 */

namespace App\Action\Api;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class ProjectDelete
 * @package App\Action\Api
 */
class ProjectDelete extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        'project' => ['string|required', "project不合法"]
    ];

    /**
     * @param array $params
     * @return bool|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $service = new DataService();
        return $service->deleteProject($params['project']);
    }
}