<?php
/**
 * Desc: 搜集请求的SQL信息 示例参考
 * User: baagee
 * Date: 2019/10/26
 * Time: 15:49
 */

/**
 * Class MySqlProfiling
 */
class MySqlProfiling
{
    /**
     * @var bool
     */
    protected static $start = false;
    /**
     * @var null mysql连接对象 mysqli 或者pdo之类的
     */
    protected static $connection = null;

    /**
     * 开启
     */
    public function start()
    {
        if (self::$start == false) {
            // TODO 获取mysql数据库连接 使用框架内置的获取连接的方法，保证mysql连接复用
            self::$connection = mysqlconnection();
            $res              = self::$connection->query('show variables where Variable_name="profiling"');
            if (isset($res[0]['Variable_name']) && $res[0]['Variable_name'] == 'profiling') {
                if (strtolower($res[0]['Value']) == 'off') {
                    self::$connection->query('set profiling=on');
                    // 在请求结束时将这次请求产生的sql提交
                    register_shutdown_function(function () {
                        try {
                            // 获取profiling
                            $profiles = self::$connection->query('show profiles');
                            foreach ($profiles as &$profile) {
                                $profile['Query']   = trim($profile['Query']);
                                $profile['explain'] = [];
                                if (
                                    stripos($profile['Query'], 'REPLACE') === 0 ||
                                    stripos($profile['Query'], 'INSERT') === 0 ||
                                    stripos($profile['Query'], 'DELETE') === 0 ||
                                    stripos($profile['Query'], 'UPDATE') === 0 ||
                                    stripos($profile['Query'], 'SELECT') === 0
                                ) {
                                    try {
                                        // 获取explain信息
                                        $explain            = self::$connection->query("EXPLAIN " . $profile['Query'])->fetchAll(\PDO::FETCH_ASSOC);
                                        $profile['explain'] = $explain;
                                    } catch (\Throwable $e) {

                                    }
                                }
                                // 获取profiling 详情
                                $profileDetail = self::$connection->query('show profile for query ' . $profile['Query_ID']);
                                if ($profile['Duration'] <= 0) {
                                    $profile['Duration'] = array_sum(array_column($profileDetail, 'Duration'));
                                }
                                $profile['detail'] = $profileDetail;
                            }
                            if (!empty($profiles) && is_array($profiles)) {
                                $this->send($profiles);
                            }
                        } catch (\Throwable $e) {

                        }
                    });
                }
            }
        }
        self::$start = true;
    }

    /**
     * 发送数据去展示 分析
     * @param array $data
     */
    protected function send(array $data)
    {
        // TODO 修改项目名字，模块
        $project = "项目名字";
        $module  = "模块名字";
        //TODO  修改成一次请求唯一的请求ID，比如随机数字...
        $trace_id = "33433 请求ID";
        // TODO 修改服务地址域名或者ip:port
        $requestUrl = 'http://your.host/api/receiver';

        if (PHP_SAPI == 'cli') {
            $url = $_SERVER['PHP_SELF'] ?? $_SERVER['SCRIPT_NAME'] ?? $_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['PATH_TRANSLATED'] ?? __FILE__;
        } else {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        $request_time  = $_SERVER['REQUEST_TIME'];
        $sql_profiling = json_encode($data);
        $data          = compact('project', 'module', 'trace_id', 'url', 'request_time', 'sql_profiling');
        $data_string   = json_encode($data);

        $ch = curl_init($requestUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)]
        );
        $result = curl_exec($ch);
    }
}
