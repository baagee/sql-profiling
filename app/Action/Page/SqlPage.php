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
class SqlPage extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        's_id' => ['integer|required', 'sql ID不合法']
    ];

    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $service = new DataService();
        $sql     = $service->getSql($params['s_id']);
        return [
            'title'          => '完整sql',
            'sql'            => $sql
        ];
    }
}
