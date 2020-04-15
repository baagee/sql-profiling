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
 * Class OnlineResultPage
 * @package App\Action\Page
 */
class OnlineResultPage extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        's_id' => ['integer|required', 'SQL ID不合法']
    ];

    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $service = new DataService();
        $sqlDetail = $service->onlineResult($params['s_id']);

        return [
            'title' => 'sql分析详情',
            'sqlDetail' => $sqlDetail,
        ];
    }
}
