<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Model;

use App\Library\IdGenerator;
use BaAGee\NkNkn\Base\ModelAbstract;

/**
 * Class ProjectModuleModel
 * @package App\Model
 */
class ProjectModuleModel extends ModelAbstract
{
    /**
     * @var string
     */
    public static $tableName = 'project_module';

    /**
     * @param $project
     * @param $module
     * @return array|mixed
     * @throws \Exception
     */
    public function findByProjectModule($project, $module)
    {
        $where = [
            'project' => ['=', $project],
            'module'  => ['=', $module],
        ];
        $res   = $this->tableObj->fields(['*'])->where($where)->select();
        if (!empty($res)) {
            return $res[0];
        } else {
            return [];
        }
    }

    /**
     * @param $project
     * @param $module
     * @return int
     * @throws \Exception
     */
    public function save($project, $module)
    {
        $xId = IdGenerator::getId();
        $this->tableObj->insert([
            'x_id'    => $xId,
            'project' => $project,
            'module'  => $module
        ]);
        return $xId;
    }

    /**
     * @param $xId
     * @return array|mixed
     * @throws \Exception
     */
    public function getByXId($xId)
    {
        $res = $this->tableObj->where([
            'x_id' => ['=', $xId],
        ])->limit(1)->select();
        return $res[0] ?? [];
    }

    /**
     * @return array|\Generator
     * @throws \Exception
     */
    public function list()
    {
        return $this->tableObj->select();
    }

    /**
     * @param $xId
     * @return int
     * @throws \Exception
     */
    public function deleteByXId($xId)
    {
        return $this->tableObj->where([
            'x_id' => ['=', $xId]
        ])->delete();
    }

    /**
     * @param $project
     * @return array|\Generator
     * @throws \Exception
     */
    public function getByProject($project)
    {
        return $this->tableObj->where([
            'project' => ['=', $project]
        ])->select();
    }
}
