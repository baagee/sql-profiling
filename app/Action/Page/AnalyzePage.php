<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:44
 */

namespace App\Action\Page;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class AnalyzePage
 * @package App\Action\Page
 */
class AnalyzePage extends ActionAbstract
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
            'title'      => 'sql分析详情',
            'analyze'    => $analyze,
            'breadcrumb' => [
                [
                    'href' => '/projects.html',
                    'name' => '项目列表',
                    'end'  => false,
                ],
                [
                    'href' => '/request/' . $analyze['request_detail']['x_id'] . '.html',
                    'name' => $analyze['request_detail']['project'] . '-' . $analyze['request_detail']['module'],
                    'end'  => false,
                ],
                [
                    'name' => 'sql分析详情',
                    'end'  => true,
                ],
            ]
        ];
    }
}
