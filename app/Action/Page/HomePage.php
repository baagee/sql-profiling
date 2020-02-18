<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:20
 */

namespace App\Action\Page;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class ProjectModuleListPage
 * @package App\Action\Page
 */
class HomePage extends ActionAbstract
{
    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $list = (new DataService())->projectModelList();

        return [
            'title'          => 'sql性能分析工具',
            'project_module' => $list
        ];
    }
}
