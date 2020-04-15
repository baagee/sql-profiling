<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/27
 * Time: 19:26
 */

namespace App\Action\Api;

use App\Service\DataService;
use BaAGee\Config\Config;
use BaAGee\NkNkn\Base\ActionAbstract;
use BaAGee\NkNkn\Constant\CoreNoticeCode;
use BaAGee\NkNkn\UserNotice;

/**
 * Class OnlineAnalyzeApi
 * @package App\Action\Api
 */
class OnlineAnalyzeApi extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        'inner_conf' => ['string|optional', "内置配置名不合法"],
        'host' => ['string|optional', "IP不合法"],
        'port' => ['string|optional', "端口不合法"],
        'user' => ['string|optional', "用户名不合法"],
        'password' => ['string|optional', "密码不合法"],
        'database' => ['string|optional', "数据库名不合法"],
        'sql' => ['string|required', "SQL不合法"],
    ];

    /**
     * @param array $params
     * @return bool|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $sql = trim($params['sql']);
        if (stripos($sql, 'select') !== 0) {
            throw new UserNotice("sql不是查询类型的语句", CoreNoticeCode::PARAMS_INVALID);
        }
        $service = new DataService();
        $dbConfig = [
            'host' => '',
            'port' => '',
            'user' => '',
            'password' => '',
            'database' => '',
        ];
        if (isset($params['inner_conf']) && !empty($params['inner_conf'])) {
            $inner = Config::get('inner', []);
            if (isset($inner[$params['inner_conf']])) {
                $dbConfig = array_merge($dbConfig, (array)$inner[$params['inner_conf']]);
            }
        }
        if (!empty($params['host'])) {
            $dbConfig['host'] = $params['host'];
        }
        if (!empty($params['port'])) {
            $dbConfig['port'] = $params['port'];
        }
        if (!empty($params['user'])) {
            $dbConfig['user'] = $params['user'];
        }
        if (!empty($params['password'])) {
            $dbConfig['password'] = $params['password'];
        }
        if (!empty($params['database'])) {
            $dbConfig['database'] = $params['database'];
        }
        foreach ($dbConfig as $k => &$v) {
            $v = trim($v);
            if ($v === '') {
                throw new UserNotice("数据库" . $k . "参数为空", CoreNoticeCode::PARAMS_INVALID);
            }
        }
        unset($v);
        $name = $params['inner_conf'];
        if (empty($name)) {
            $name = strval(time());
        }
        return $service->onlineAnalyze($name, $dbConfig, $params['sql']);
    }
}
