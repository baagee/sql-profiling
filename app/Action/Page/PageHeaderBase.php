<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/27
 * Time: 09:12
 */

namespace App\Action\Page;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class PageHeaderBase
 * @package App\Action\Page
 */
abstract class PageHeaderBase extends ActionAbstract
{
    /**
     * @return array
     * @throws \Exception
     */
    protected function getHeaderMenu()
    {
        return (new DataService())->projectModelList();
    }
}
