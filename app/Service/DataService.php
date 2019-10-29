<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Service;

use App\Model\ProjectModuleModel;
use App\Model\RequestsModel;
use App\Model\SqlDetailModel;
use BaAGee\MySQL\DB;

/**
 * Class Data
 * @package App\Service
 */
class DataService
{
    /**
     * 接受要分析的数据
     * @param $project
     * @param $module
     * @param $url
     * @param $traceId
     * @param $requestTime
     * @param $sqlProfiling
     * @return bool
     * @throws \Exception
     */
    public function saveReceive($project, $module, $url, $traceId, $requestTime, $sqlProfiling)
    {
        /*首先查找project-module是否存在*/
        $projectModuleModel = new ProjectModuleModel();
        $projectModule      = $projectModuleModel->findByProjectModule($project, $module);
        $sqlCount           = count($sqlProfiling);
        $allCostTime        = array_sum(array_column($sqlProfiling, 'Duration')) * 1000;
        $connection         = DB::getInstance();
        $requestsModel      = new RequestsModel();
        $sqlDetailModel     = new SqlDetailModel();
        $connection->beginTransaction();
        try {
            if (!empty($projectModule)) {
                $xid = $projectModule['x_id'];
            } else {
                $xid = $projectModuleModel->save($project, $module);
            }
            $lId = $requestsModel->save($xid, $traceId, $url, $requestTime, $allCostTime, $sqlCount);
            $sqlDetailModel->batchSave($lId, $sqlProfiling);
            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollback();
            return false;
        }
    }

    public function clearModuleRequest($xId)
    {
        $requestModel = new RequestsModel();
        $lIds         = $requestModel->getLIdByXIds([$xId]);
        if (!empty($lIds)) {
            $sqlDetailModel = new SqlDetailModel();
            $connection     = DB::getInstance();
            $connection->beginTransaction();
            try {
                $sqlDetailModel->deleteByLIds($lIds);
                $requestModel->deleteByXId($xId);
                $connection->commit();
                return true;
            } catch (\Exception $e) {
                $connection->rollback();
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function projectModelList()
    {
        $list          = (new ProjectModuleModel())->list();
        $projectModule = [];
        foreach ($list as $item) {
            $projectModule[$item['project']][] = [
                'module' => $item['module'],
                'x_id'   => $item['x_id']
            ];
        }
        $newList = [];
        foreach ($projectModule as $project => $modules) {
            $modules   = array_chunk($modules, 4);
            $newList[] = [
                'project' => $project,
                'modules' => $modules
            ];
        }
        return ['header_menu' => $projectModule, 'list' => array_chunk($newList, 2)];
    }

    /**
     * 根据项目-模块搜索请求列表
     * @param        $xId
     * @param        $page
     * @param        $size
     * @param string $url
     * @param string $traceId
     * @param string $startTime
     * @param string $endTime
     * @return array
     * @throws \Exception
     */
    public function searchRequest($xId, $page, $size, $url = '', $traceId = '', $startTime = '', $endTime = '')
    {
        $requestModel = new RequestsModel();
        return $requestModel->search($xId, $page, $size, $url, $traceId, $startTime, $endTime);
    }

    public function analyze($lId)
    {
        $requestModel       = new RequestsModel();
        $request            = $requestModel->getByLId($lId);
        $projectModuleModel = new ProjectModuleModel();
        $pm                 = $projectModuleModel->getByXId($request['x_id']);
        $requestDetail      = array_merge($pm, $request);

        $sqlDetailModel = new SqlDetailModel();
        $sqlDetailList  = $sqlDetailModel->getByLId($lId);
        $randColor      = function () {
            $c = '';
            while (strlen($c) < 6) {
                $c .= sprintf("%02X", mt_rand(0, 255));
            }
            return $c;
        };

        foreach ($sqlDetailList as &$item) {
            $detail      = json_decode($item['detail'], true);
            $pieData     = $timeLine = [];
            $beforeTotal = 0;
            foreach ($detail as $index => &$dd) {
                $dd['Duration'] = number_format($dd['Duration'] * 1000, 6, '.', '');
                $pieData[]      = [
                    'name'  => $dd['Status'],
                    'value' => $dd['Duration'],
                ];

                $afterTotal  = $beforeTotal;
                $us          = $dd['Duration'] * 1000;
                $afterTotal  += $us;
                $afterTotal  = number_format($afterTotal, 2, '.', '');
                $timeLine[]  = [
                    'name'      => $dd['Status'],
                    'itemStyle' => ['normal' => ['color' => '#' . $randColor()]],
                    'value'     => [
                        $index,
                        $beforeTotal,
                        $afterTotal,
                        $us
                    ]
                ];
                $beforeTotal = $afterTotal;
            }
            $item['detail']        = $detail;
            $item['pie_data']      = json_encode($pieData);
            $item['timeline_data'] = json_encode($timeLine);
            $item['legend']        = json_encode(array_column($pieData, 'name'));
        }
        // var_dump($sqlDetailList);
        // die;

        return [
            'request_detail'  => $requestDetail,
            'sql_detail_list' => $sqlDetailList
        ];
    }
}