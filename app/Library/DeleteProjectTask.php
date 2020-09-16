<?php
/**
 * Desc:
 * User: baagee
 * Date: 2020/9/16
 * Time: 下午4:48
 */

namespace App\Library;

use App\Service\DataService;
use BaAGee\NkNkn\CLIApp;

class DeleteProjectTask extends CLIApp
{
    public function run($params = [])
    {
        $service = new DataService();
        echo '准备删除数据：' . json_encode($params) . PHP_EOL;
        try {
            $service->deleteProject($params['project']);
            echo '删除数据成功' . PHP_EOL;
        } catch (\Exception $e) {
            echo '删除数据失败：' . $e->getMessage() . PHP_EOL;
        }
    }

    public function shutdownFunc()
    {
        echo __METHOD__ . PHP_EOL;
        parent::shutdownFunc();
    }
}
