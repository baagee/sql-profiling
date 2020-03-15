<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Model;

use BaAGee\NkNkn\Base\ModelAbstract;
use BaAGee\NkNkn\IdGenerator;

/**
 * Class SqlDetailModel
 * @package App\Model
 */
class SqlDetailModel extends ModelAbstract
{
    /**
     * @var string
     */
    public static $tableName = 'sql_detail';

    /**
     * @param       $lId
     * @param array $profiling
     * @return bool
     * @throws \Exception
     */
    public function batchSave($lId, array $profiling)
    {
        $rows = [];
        foreach ($profiling as $item) {
            $rows[] = [
                's_id'     => IdGenerator::getOne(false),
                'l_id'     => $lId,
                'query_id' => $item['Query_ID'],
                'cost'     => $item['Duration'] * 1000,
                'sql'      => $item['Query'],
                'profile'  => json_encode($item['detail']),
                'explain'  => json_encode($item['explain'])
            ];
        }
        $this->tableObj->insert($rows);
        return true;
    }

    /**
     * @param $lId
     * @return array|\Generator
     * @throws \Exception
     */
    public function getByLId($lId)
    {
        return $this->tableObj->where([
            'l_id' => ['=', $lId]
        ])->select();
    }

    /**
     * @param array $lIds
     * @return int
     * @throws \Exception
     */
    public function deleteByLIds(array $lIds)
    {
        return $this->tableObj->where([
            'l_id' => ['in', $lIds]
        ])->delete();
    }

    /**
     * @param $sId
     * @return array|\Generator
     * @throws \Exception
     */
    public function getBySId($sId)
    {
        return $this->tableObj->where([
            's_id' => ['=', $sId]
        ])->limit(1)->select();
    }
}
