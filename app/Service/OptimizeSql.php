<?php
/**
 * Desc: sql优化建议
 * User: baagee
 * Date: 2019/12/26
 * Time: 上午10:40
 */

namespace App\Service;
/**
 * Class OptimizeSql
 * @package App\Service
 */
class OptimizeSql
{
    /**
     * 获取sql的优化建议
     * @param $sql
     * @param $explainList
     * @param $profilingList
     * @param $costTime
     * @return array
     */
    public function getSuggestions($sql, $explainList, $profilingList, $costTime)
    {
        $suggestions = [];
        if ($this->isSelectSql($sql)) {
            //只有查询语句才给出建议
            if ($this->isSelectStar($sql)) {
                $suggestions[] = "不建议使用【SELECT * 】，会降低查询效率";
            }
            $profilingList = array_column($profilingList, 'Duration', 'Status');
            $profilingList = array_change_key_case($profilingList, CASE_LOWER);

            if (ceil($profilingList['sorting result'] / $costTime * 100) > 10) {
                $suggestions[] = "由于【sorting result】占比较大(表示表数据排序)，建议对order by后的字段加上合适的索引";
            }

            if (ceil($profilingList['sending data'] / $costTime * 100) > 15) {
                $sug = "由于【sending data】占比较大(表示查询数据量大)，建议只查询必要的字段";
                //
                //判断是否使用了limit
                if (!$this->isUseLimit($sql)) {
                    $sug .= ", 增加limit 限制查询结果的记录数";
                }
                $suggestions[] = $sug;
            }
            if (ceil($profilingList['creating sort index'] / $costTime * 100) > 10) {
                $suggestions[] = "由于【creating sort index】占比较大(需要额外进行外部的排序),一般和order by有者直接关系，需要对order by的字段创建适当的索引";
            }
            if (!empty($explainList)) {
                foreach ($explainList as $explain) {
                    if (strtolower($explain['type']) == 'all') {
                        $suggestions[] = "由于explain中的type=ALL，mysql会进行全表扫描找到匹配的结果行，效率最慢，建议增加索引，通过索引查询来优化";
                    } elseif (strtolower($explain['type']) == 'index') {
                        $suggestions[] = "由于explain中的type=index，和ALL一样，仅次于ALL，不同就是mysql只需扫描索引树，这通常比ALL快一些，但是数据量大时，开销依然比较大";
                    }
                    if (stripos($explain['Extra'], 'Using filesort') !== false) {
                        $suggestions[] = "由于存在【Using filesort】需要额外进行外部的排序，导致该问题的原因一般和order by有者直接关系, 建议对order by后的字段加上合适的索引";
                    }
                }
            }
        }

        return $suggestions;
    }

    /**
     * 判断是否是select *
     * @param $sql
     * @return bool
     */
    protected function isSelectStar($sql)
    {
        $regexp = "/^SELECT\s*\*\s*FROM/i";
        preg_match($regexp, $sql, $match);
        return count($match) > 0;
    }

    /**
     * @param $sql
     * @return bool
     */
    protected function isSelectSql($sql)
    {
        $regexp = "/^SELECT/i";
        preg_match($regexp, $sql, $match);
        return count($match) > 0;
    }

    /**
     * @param $sql
     * @return bool
     */
    protected function isUseLimit($sql)
    {
        $regexp = "/limit\s*\d+/i";
        preg_match($regexp, $sql, $match);
        return count($match) > 0;
    }
}
