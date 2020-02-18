<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/30
 * Time: 上午12:02
 */

use App\Action\Page\AnalyzePage;
use App\Action\Api\ProjectDeleteApi;
use App\Action\Api\ClearModuleRequestApi;
use App\Action\Page\ProjectModuleListPage;
use App\Action\Page\ReadmePage;
use App\Action\Api\ReceiverApi;
use App\Action\Page\RequestListPage;
use App\Action\Api\RequestListApi;
use App\Middleware\ReturnHtml;
use App\Middleware\ReturnJson;
use App\Middleware\PageCache;
use App\Action\Page\HomePage;

return [
    '/projects.html'       => [
        'method'     => 'get',
        'callback'   => ProjectModuleListPage::class,
        'middleware' => [ReturnHtml::class]
    ],
    '/'                    => [
        'method'     => 'get',
        'callback'   => HomePage::class,
        'middleware' => [ReturnHtml::class]
    ],
    '/api/project/delete'  => [
        'method'     => 'post',
        'callback'   => ProjectDeleteApi::class,
        'middleware' => [ReturnJson::class]
    ],
    '/request/{x_id}.html' => [
        'method'     => 'get',
        'callback'   => RequestListPage::class,
        'middleware' => [ReturnHtml::class]
    ],
    '/api/request/list'    => [
        'method'     => 'get',
        'callback'   => RequestListApi::class,
        'middleware' => [ReturnJson::class]
    ],
    '/analyze/{l_id}.html' => [
        'method'     => 'get',
        'callback'   => AnalyzePage::class,
        'middleware' => [PageCache::class, ReturnHtml::class]
    ],

    '/api/receiver' => [
        'method'     => 'post',
        'callback'   => ReceiverApi::class,
        'middleware' => [ReturnJson::class]
    ],

    '/readme.html'       => [
        'method'     => 'get',
        'callback'   => ReadmePage::class,
        'middleware' => [PageCache::class, ReturnHtml::class]
    ],
    '/api/request/clear' => [
        'method'     => 'post',
        'callback'   => ClearModuleRequestApi::class,
        'middleware' => [ReturnJson::class]
    ],
    '/sql/{s_id}.html'   => [
        'method'     => 'get',
        'callback'   => \App\Action\Page\SqlPage::class,
        'middleware' => [PageCache::class, ReturnHtml::class]
    ]
];
