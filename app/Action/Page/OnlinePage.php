<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:20
 */

namespace App\Action\Page;

use App\Service\DataService;
use BaAGee\Config\Config;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class OnlinePage
 * @package App\Action\Page
 */
class OnlinePage extends ActionAbstract
{
    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $inner = array_keys(Config::get('inner'));
        $hisList = (new DataService())->getOnlineHistory(100);
        return [
            'title' => '在线分析',
            'inner_conf' => $inner,
            'history_list' => $hisList
        ];
    }
}
