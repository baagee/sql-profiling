<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/30
 * Time: 上午12:02
 */

use App\Action\Page\AnalyzePage;
use App\Action\Api\ProjectDelete;
use App\Action\Api\ClearModuleRequestApi;
use App\Action\Page\ProjectModuleListPage;
use App\Action\Page\ReadmePage;
use App\Action\Api\ReceiverApi;
use App\Action\Page\RequestListPage;
use App\Action\Api\RequestListApi;
use App\Middleware\ReturnHtml;
use \App\Middleware\ReturnJson;
use \App\Middleware\PageCache;

return [
    '/'                   => [
        'method'     => 'get',
        'callback'   => ProjectModuleListPage::class,
        'middleware' => [ReturnHtml::class]
    ],
    '/api/project/delete' => [
        'method'     => 'post',
        'callback'   => ProjectDelete::class,
        'middleware' => [ReturnJson::class]
    ],
    '/request/{x_id}'     => [
        'method'     => 'get',
        'callback'   => RequestListPage::class,
        'middleware' => [ReturnHtml::class]
    ],
    '/api/request/list'   => [
        'method'     => 'get',
        'callback'   => RequestListApi::class,
        'middleware' => [ReturnJson::class]
    ],
    '/analyze/{l_id}'     => [
        'method'     => 'get',
        'callback'   => AnalyzePage::class,
        'middleware' => [PageCache::class, ReturnHtml::class]
    ],

    '/api/receiver' => [
        'method'     => 'post',
        'callback'   => ReceiverApi::class,
        'middleware' => [ReturnJson::class]
    ],

    '/readme'            => [
        'method'     => 'get',
        'callback'   => ReadmePage::class,
        'middleware' => [PageCache::class, ReturnHtml::class]
    ],
    '/api/request/clear' => [
        'method'     => 'post',
        'callback'   => ClearModuleRequestApi::class,
        'middleware' => [ReturnJson::class]
    ]
];
