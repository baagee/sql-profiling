<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:44
 */

namespace App\Action\Page;

use App\Service\DataService;

/**
 * Class AnalyzePage
 * @package App\Action\Page
 */
class AnalyzePage extends PageHeaderBase
{
    /**
     * @var array
     */
    protected $paramRules = [
        'l_id' => ['integer|required', '请求ID不合法']
    ];

    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
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
