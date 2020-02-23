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
use BaAGee\Log\Log;
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
        $projectModuleModel = new ProjectModuleModel();
        for ($i = 0; $i <= 5; $i++) {
            /*首先查找project-module是否存在*/
            $projectModule  = $projectModuleModel->findByProjectModule($project, $module);
            $sqlCount       = count($sqlProfiling);
            $allCostTime    = array_sum(array_column($sqlProfiling, 'Duration')) * 1000;
            $connection     = DB::getInstance();
            $requestsModel  = new RequestsModel();
            $sqlDetailModel = new SqlDetailModel();
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
                Log::warning("retry:" . $i . " save profiling failed: " . $e->getMessage());
                $i++;
            }
            usleep(3000);
        }
        return false;
    }

    /**
     * @param $xId
     * @return bool
     * @throws \Exception
     */
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
        $colors        = [
            "#9933CC", "#66CCCC", "#99CC33", "#FF9966", "#CC3399", "#00CC00", "#FF6666", '#FF9900', '#FF99CC', '#99CC99',
            '#3399CC', '#FF9999', '#FFCC33', '#993366', '#99CCFF', '#003399', '#FF33CC', '#99CCCC', '#FF6600', '#FF0033'
        ];
        $list          = (new ProjectModuleModel())->list();
        $projectModule = [];
        $i             = 0;
        $max           = count($colors);
        foreach ($list as $item) {
            if ($i == $max) {
                $i = 0;
            }
            $projectModule[$item['project']][] = [
                'module' => $item['module'],
                'x_id'   => $item['x_id'],
                'color'  => $colors[$i]
            ];
            $i++;
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
     * @param int    $showHost
     * @param string $startTime
     * @param string $endTime
     * @return array
     * @throws \Exception
     */
    public function searchRequest($xId, $page, $size, $url = '', $traceId = '', $showHost = 0, $startTime = '', $endTime = '')
    {
        $requestModel = new RequestsModel();
        $ret          = $requestModel->search($xId, $page, $size, $url, $traceId, $startTime, $endTime);
        if ($showHost == 0) {
            $this->removeUrlHost($ret['list']);
        }
        return $ret;
    }

    /**
     * @param $list
     */
    protected function removeUrlHost(&$list)
    {
        array_walk($list, function (&$v) {
            $parseUrl = parse_url($v['url']);
            if (is_array($parseUrl)) {
                if (isset($parseUrl['path'])) {
                    $v['url'] = $parseUrl['path'];
                } else {
                    $v['url'] = '/';
                }
                if (isset($parseUrl['query']) && !empty($parseUrl['query'])) {
                    $v['url'] .= '?' . $parseUrl['query'];
                }
            }
        });
    }

    /**
     * 根据时间获取颜色
     * @param $time
     * @return string
     */
    protected static function getBadgeColor($time)
    {
        switch ($time) {
            case $time < 1:
                return '#00DD00';
            case $time >= 1 && $time < 3:
                return '#66DD00';
            case $time >= 3 && $time < 6:
                return '#99DD00';
            case $time >= 6 && $time < 12:
                return '#EEEE00';
            case $time >= 12 && $time < 24:
                return '#DDAA00';
            case $time >= 24 && $time < 48:
                return '#EE7700';
            case $time >= 48 && $time < 96:
                return '#E63F00';
            case $time >= 96:
            default:
                return '#CC0000';
        }
    }

    /**
     * 随机颜色
     * @return string
     */
    protected static function randomColor()
    {
        $c = '';
        while (strlen($c) < 6) {
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return '#' . $c;
    }

    /**
     * 获取分析的数据
     * @param $lId
     * @return array
     * @throws \Exception
     */
    public function analyze($lId)
    {
        $requestModel       = new RequestsModel();
        $request            = $requestModel->getByLId($lId);
        $projectModuleModel = new ProjectModuleModel();
        $pm                 = $projectModuleModel->getByXId($request['x_id']);
        $requestDetail      = array_merge($pm, $request);

        $requestDetail['analyze_time'] = date('Y-m-d H:i:s');

        $sqlDetailModel = new SqlDetailModel();
        $sqlDetailList  = $sqlDetailModel->getByLId($lId);
        $optimize       = new OptimizeSql();
        foreach ($sqlDetailList as &$item) {
            $profile     = json_decode($item['profile'], true);
            $pieData     = $timeLine = [];
            $beforeTotal = 0;
            $colors      = [];
            $dup         = [];
            foreach ($profile as $index => &$dd) {
                $dd['Duration'] = number_format($dd['Duration'] * 1000, 6, '.', '');
                if (isset($dup[$dd['Status']])) {
                    $v            = $dup[$dd['Status']] + 1;
                    $dd['Status'] = $dd['Status'] . '-' . $v;
                } else {
                    $v = 0;
                }
                $dup[$dd['Status']] = $v;
                $pieData[]          = [
                    'name'  => $dd['Status'],
                    'value' => $dd['Duration'],
                ];

                $afterTotal = $beforeTotal;
                $us         = $dd['Duration'] * 1000;
                $afterTotal += $us;
                $afterTotal = number_format($afterTotal, 2, '.', '');

                $color = '';
                while (true) {
                    $color = self::randomColor();
                    if (!in_array($color, $colors)) {
                        break;
                    }
                }
                //当前所有颜色
                $colors[] = $color;
                // 当前的颜色
                $dd['color'] = $color;
                $timeLine[]  = [
                    'name'      => $dd['Status'],
                    'itemStyle' => ['normal' => ['color' => $color]],
                    'value'     => [$index, $beforeTotal, $afterTotal, $us]
                ];
                $beforeTotal = $afterTotal;
            }
            unset($dd);
            $item['profile']    = $profile;
            $item['colors']     = json_encode($colors);
            $item['time_color'] = self::getBadgeColor($item['cost']);

            $explain = json_decode($item['explain'], true) ?? [];
            if (count($explain) > 0 && count($explain) === count($explain, COUNT_RECURSIVE)) {
                // 一维数组转化为2纬
                unset($item['explain']);
                $item['explain'][] = $explain;
            } else {
                $item['explain'] = $explain;
            }
            $item['explication']   = MySqlExplainRemark::getExplication($item['explain']);
            $item['pie_data']      = json_encode($pieData);
            $item['timeline_data'] = json_encode($timeLine);
            $item['legend']        = json_encode(array_column($pieData, 'name'));
            $item['suggestions']   = $optimize->getSuggestions($item['sql'], $item['explain'], $item['profile'], $item['cost']);
            $item['score']         = MySqlExplainRemark::getScore(count($item['suggestions']), $item['explain']);
            if (strlen($item['sql']) > 8192) {
                $url         = '/sql/' . $item['s_id'] . '.html';
                $item['sql'] = substr($item['sql'], 0, 8192) .
                    '<a href="javascript:;" onclick="showSql(\'' . $url . '\')" title="点击查看完整sql">...</a>';
            }
        }
        unset($item);
        return [
            'request_detail'  => $requestDetail,
            'sql_detail_list' => $sqlDetailList
        ];
    }

    /**
     * 删除这个项目所有的相关请求和sql
     * @param $project
     * @return bool
     * @throws \Exception
     */
    public function deleteProject($project)
    {
        $projectModuleModel = new ProjectModuleModel();
        $pms                = $projectModuleModel->getByProject($project);
        $connection         = DB::getInstance();
        $connection->beginTransaction();
        try {
            foreach ($pms as $pm) {
                $projectModuleModel->deleteByXId($pm['x_id']);
                if ($this->clearModuleRequest($pm['x_id']) == false) {
                    throw new \Exception("清空失败");
                }
            }
            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollback();
            return false;
        }
    }

    /**
     * 获取完整sql信息
     * @param $sId
     * @return mixed|string
     * @throws \Exception
     */
    public function getSql($sId)
    {
        $sqlDetailModel = new SqlDetailModel();
        return $sqlDetailModel->getBySId($sId)[0]['sql'] ?? '';
    }

    /**
     * 统计各个项目的请求数量
     * @return array
     * @throws \Exception
     */
    public function getModuleRequestCount()
    {
        $requestModel = new RequestsModel();
        $res          = $requestModel->countGroupByXId();
        if (!empty($res)) {
            return array_column($res, 'c', 'x_id');
        } else {
            return [];
        }
    }
}
