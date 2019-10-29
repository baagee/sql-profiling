<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Model;

use BaAGee\NkNkn\Base\ModelAbstract;

class SqlDetailModel extends ModelAbstract
{
    public static $tableName = 'sql_detail';

    public function batchSave($lId, array $profiling)
    {
        $rows = [];
        foreach ($profiling as $item) {
            $rows[] = [
                's_id'     => intval(microtime(true) * 1000) + mt_rand(10000, 99999),
                'l_id'     => $lId,
                'query_id' => $item['Query_ID'],
                'cost'     => $item['Duration'] * 1000,
                'sql'      => $item['Query'],
                'detail'   => json_encode($item['detail'])
            ];
        }
        $this->tableObj->insert($rows);
        return true;
    }

    public function getByLId($lId)
    {
        return $this->tableObj->where([
            'l_id' => ['=', $lId]
        ])->select();
    }

    public function deleteByLIds(array $lIds)
    {
        return $this->tableObj->where([
            'l_id' => ['in', $lIds]
        ])->delete();
    }
}
