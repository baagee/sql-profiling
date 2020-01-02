<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:20
 */

namespace App\Action\Page;

/**
 * Class ProjectModuleListPage
 * @package App\Action\Page
 */
class ProjectModuleListPage extends PageHeaderBase
{
    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $list = $this->getHeaderMenu();
        return [
            'title'          => '项目-模块列表',
            'project_module' => $list
        ];
    }
}
