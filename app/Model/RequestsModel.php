<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Model;

use BaAGee\MySQL\DB;
use BaAGee\NkNkn\Base\ModelAbstract;

/**
 * Class Requests
 * @package App\Model
 */
class RequestsModel extends ModelAbstract
{
    /**
     * @var string
     */
    public static $tableName = 'requests';

    /**
     * @param     $xId
     * @param     $traceId
     * @param     $url
     * @param int $requestTime
     * @param     $allCostTime
     * @param     $sqlCount
     * @return int
     * @throws \Exception
     */
    public function save($xId, $traceId, $url, int $requestTime, $allCostTime, $sqlCount)
    {
        $lId = intval(microtime(true) * 1000) + mt_rand(10000, 99999);
        $this->tableObj->insert([
            'x_id'          => $xId,
            'l_id'          => $lId,
            'trace_id'      => $traceId,
            'url'           => $url,
            'request_time'  => date('Y-m-d H:i:s', $requestTime),
            'all_cost_time' => $allCostTime,
            'sql_count'     => $sqlCount
        ]);
        return $lId;
    }

    /**
     * @param        $xId
     * @param        $page
     * @param        $limit
     * @param string $url
     * @param string $traceId
     * @param string $startTime
     * @param string $endTime
     * @return array
     * @throws \Exception
     */
    public function search($xId, $page, $limit, $url = '', $traceId = '', $startTime = '', $endTime = '')
    {
        $pdata = [
            ":xId" => $xId
        ];
        $where = "`x_id`=:xId";
        $co    = [
            'x_id' => ['=', $xId]
        ];
        if (!empty($url)) {
            $co['url']     = ['like', '%' . $url . '%'];
            $where         .= " AND `url` like :url";
            $pdata[":url"] = '%' . $url . '%';
        }
        if (!empty($traceId)) {
            $co['trace_id']    = ['=', $traceId];
            $where             .= " AND `trace_id` = :traceId";
            $pdata[':traceId'] = $traceId;
        }
        if (!empty($startTime = trim($startTime)) && !empty($endTime = trim($endTime))) {
            $co['request_time'] = ['between', [$startTime, $endTime]];
            $where              .= " AND `request_time` BETWEEN :st AND :et";
            $pdata[":st"]       = $startTime;
            $pdata[":et"]       = $endTime;
        }

        $offset = ($page - 1) * $limit;
        $sql    = <<<SQL
SELECT `l_id`, `trace_id`, `url`, `request_time`, `all_cost_time`, `sql_count`, `create_time` FROM `requests` r inner join (
select `id` from `requests` WHERE $where ORDER BY `create_time` DESC limit $offset,$limit) b on r.id=b.id
SQL;

        $db    = DB::getInstance();
        $list  = $db->query($sql, $pdata);
        $count = $this->tableObj->fields(['count(*) as c'])->where($co)->select()[0]['c'] ?? 0;
        return compact('list', 'count');
    }

    public function getByLId($lId)
    {
        $res = $this->tableObj->where([
            'l_id' => ['=', $lId]
        ])->limit(1)->select();
        return $res[0] ?? [];
    }

    public function getLIdByXIds(array $xIds)
    {
        $list = $this->tableObj->where([
            'x_id' => ['in', $xIds]
        ])->fields(['l_id'])->select();
        if (!empty($list)) {
            return array_column($list, 'l_id');
        }
        return [];
    }

    public function deleteByXId($xId)
    {
        return $this->tableObj->where([
            'x_id' => ['=', $xId]
        ])->delete();
    }
}
