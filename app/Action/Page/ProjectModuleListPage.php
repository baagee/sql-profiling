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
class ProjectModuleListPage extends ActionAbstract
{
    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $ds        = new DataService();
        $list      = $ds->projectModelList();
        $xId2Count = $ds->getModuleRequestCount();
        return [
            'title'          => '项目-模块列表',
            'project_module' => $list['list']??[],
            'xid2count'      => $xId2Count,
            'breadcrumb'     => [
                [
                    'name' => '项目列表',
                    'end'  => true,
                ]
            ]
        ];
    }
}
