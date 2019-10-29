<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:44
 */

namespace App\Action\Page;

use App\Service\DataService;

class AnalyzePage extends PageHeaderBase
{
    protected $paramRules = [
        'l_id' => ['integer|required', '请求ID不合法']
    ];

    protected function execute(array $params = [])
    {
        $service = new DataService();
        $analyze = $service->analyze($params['l_id']);
        return [
            'title'          => 'sql分析详情',
            'project_module' => $this->getHeaderMenu(),
            'analyze'        => $analyze
        ];
    }
}
