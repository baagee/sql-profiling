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
 * Class OnlineSqlModel
 * @package App\Model
 */
class OnlineSqlModel extends ModelAbstract
{
    /**
     * @var string
     */
    public static $tableName = 'online_sql';

    /**
     * @param       $hash
     * @param array $profiling
     * @return int
     * @throws \Exception
     */
    public function save($hash, array $profiling)
    {
        $profiling = array_values($profiling);
        $profiling = $profiling[0];
        $id = IdGenerator::getOne(false);

        $rows[] = [
            's_id' => $id,
            'hash' => $hash,
            'query_id' => $profiling['Query_ID'],
            'cost' => $profiling['Duration'] * 1000,
            'sql' => $profiling['Query'],
            'profile' => json_encode($profiling['detail']),
            'explain' => json_encode($profiling['explain'])
        ];
        $this->tableObj->insert($rows);
        return $id;
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

    /**
     * 获取在线分析的历史记录
     * @param int $limit
     * @return array|\Generator
     * @throws \Exception
     */
    public function getOnlineHistory($limit = 100)
    {
        return $this->tableObj->fields(['s_id', 'create_time', 'sql','cost'])->orderBy(['id' => 'desc'])->limit($limit)->select();
    }

    /**
     * 检查一分钟内是否重复
     * @param $hash
     * @return bool
     * @throws \Exception
     */
    public function checkExists($hash)
    {
        $has = $this->tableObj->where(['hash' => ['=', $hash]])->orderBy(['id' => 'desc'])
            ->fields(['create_time'])->limit(1)->select();
        if (empty($has)) {
            return false;
        }
        $lastTime = $has[0]['create_time'] ?? '1995-01-09';
        $lastTime = strtotime($lastTime);
        return time() - 60 < $lastTime;
    }
}
