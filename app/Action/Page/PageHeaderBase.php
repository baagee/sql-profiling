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

abstract class PageHeaderBase extends ActionAbstract
{
    protected function getHeaderMenu()
    {
        return (new DataService())->projectModelList();
    }
}
