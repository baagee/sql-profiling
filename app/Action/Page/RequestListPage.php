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
 * Class RequestListPage
 * @package App\Action\Page
 */
class RequestListPage extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        'x_id' => ['integer|required', 'url不合法']
    ];

    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $list    = (new DataService())->projectModelList();
        $detail  = $module = [];
        $project = '';
        foreach ($list['header_menu'] as $project => $modules) {
            foreach ($modules as $module) {
                if ($module['x_id'] == $params['x_id']) {
                    $detail = $list['header_menu'][$project];
                    break;
                }
            }
            if (!empty($detail)) {
                break;
            }
        }
        return [
            'title'          => $project . '-' . $module['module'] . '模块请求列表',
            'x_id'           => $params['x_id'],
            'project_module' => $list,
            'cur_modules'    => $detail,
            'cur_project'    => $project
        ];
    }
}
