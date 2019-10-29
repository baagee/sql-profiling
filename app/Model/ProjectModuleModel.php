<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Model;

use BaAGee\NkNkn\Base\ModelAbstract;

class ProjectModuleModel extends ModelAbstract
{
    public static $tableName = 'project_module';

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

    public function save($project, $module)
    {
        $xId = intval(microtime(true) * 1000) + mt_rand(10000, 99999);
        $this->tableObj->insert([
            'x_id'    => $xId,
            'project' => $project,
            'module'  => $module
        ]);
        return $xId;
    }

    public function getByXId($xId)
    {
        $res = $this->tableObj->where([
            'x_id' => ['=',$xId],
        ])->limit(1)->select();
        return $res[0] ?? [];
    }

    public function list()
    {
        return $this->tableObj->select();
    }
}
