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

class ProjectDelete extends ActionAbstract
{
    protected $paramRules = [
        'project' => ['string|required', "project不合法"]
    ];

    protected function execute(array $params = [])
    {
        $service = new DataService();
        return $service->deleteProject($params['project']);
    }
}