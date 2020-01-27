<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/12/25
 * Time: 下午7:44
 */

namespace App\Service;

/**
 * Class MySqlExplainRemark
 * @package App\Service
 */
class MySqlExplainRemark
{
    /**
     *
     */
    protected const SQL_SCORE_MAP = [
        //    null > system > const > eq_ref > ref > fulltext > ref_or_null > index_merge > unique_subquery > index_subquery > range > index > ALL
        // 基础分 根据建议数计算
        'all'             => 6.0,
        'index'           => 7.0,
        'range'           => 7.5,
        'index_subquery'  => 7.5,
        'unique_subquery' => 8.0,
        'index_merge'     => 8.0,
        'ref_or_null'     => 8.0,
        'fulltext'        => 8.5,
        'ref'             => 9.0,
        'eq_ref'          => 9.0,
        'const'           => 9.5,
        'system'          => 9.5,
        'null'            => 10.0,
    ];
    /**
     *
     */
    protected const REMARK_MAP = [
        'select_type'   => [
            'INSERT'               => '插入数据语句',
            'REPLACE'              => '插入的数据的唯一索引或者主键索引与之前的数据有重复的情况，将会删除原先的数据，然后再进行添加',
            'UPDATE'               => '更新数据语句',
            'DELETE'               => '删除数据语句',
            'SIMPLE'               => '简单SELECT，不使用UNION或子查询等',
            'PRIMARY'              => '子查询中最外层查询，查询中若包含任何复杂的子部分，最外层的select被标记为PRIMARY',
            'UNION'                => 'UNION中的第二个或后面的SELECT语句',
            'DEPENDENT UNION'      => 'UNION中的第二个或后面的SELECT语句，取决于外面的查询',
            'UNION RESULT'         => 'UNION的结果，union语句中第二个select开始后面所有select',
            'SUBQUERY'             => '子查询中的第一个SELECT，结果不依赖于外部查询',
            'DEPENDENT SUBQUERY'   => '子查询中的第一个SELECT，依赖于外部查询',
            'DERIVED'              => '派生表的SELECT, FROM子句的子查询',
            'UNCACHEABLE SUBQUERY' => '一个子查询的结果不能被缓存，必须重新评估外链接的第一行',
        ],
        // 效率优先级
        //null > system > const > eq_ref > ref > fulltext > ref_or_null > index_merge > unique_subquery > index_subquery > range > index > ALL
        'type'          => [
            'all'             => 'Full Table Scan，即全表扫描，意味着mysql需要从头到尾去查找所需要的行。通常情况下这需要增加索引来进行优化了',
            'index'           => 'Full Index Scan， 和ALL一样，不同就是mysql只需扫描索引树，这通常比ALL快一些。index与ALL区别为index类型只遍历索引树,开销依然非常大',
            'range'           => '范围扫描，一个有限制的索引扫描。key 列显示使用了哪个索引。当使用=、 <>、>、>=、<、<=、IS NULL、<=>、BETWEEN 或者 IN 操作符,用常量比较关键字列时',
            'index_subquery'  => '这种连接类型类似 unique_subquery。它用子查询来代替in，不过它用于在子查询中没有唯一索引的情况下，例如以下形式：value in (select key_column from single_table where some_expr)',
            'unique_subquery' => '该类型替换了下面形式的IN子查询的ref：value IN (SELECT primary_key FROM single_table WHERE some_expr) unique_subquery是一个索引查找函数，可以完全替换子查询，效率更高',
            'index_merge'     => '表示使用了索引合并的优化方法',
            'ref_or_null'     => '类似 ref，不同的是mysql会在检索的时候额外的搜索包含null 值的记录',
            'fulltext'        => '全文索引检索，要注意，全文索引的优先级很高，若全文索引和普通索引同时存在时，mysql不管代价，优先选择使用全文索引',
            'ref'             => '一种索引访问，它返回所有匹配某个单个值的行。此类索引访问只有当使用非唯一性索引或唯一性索引非唯一性前缀时才会发生。这个类型跟eq_ref不同的是，它用在关联操作只使用了索引的最左前缀，或者索引不是UNIQUE和PRIMARY KEY。ref可以用于使用=或<=>操作符的带索引的列',
            'eq_ref'          => '类似ref，区别就在使用的索引是唯一索引，对于每个索引键值，表中只有一条记录匹配，简单来说，就是多表连接中使用primary key或者 unique key作为关联条件',
            'const'           => '当确定最多只会有一行匹配的时候，MySQL优化器会在查询前读取它而且只读取一次，因此非常快。当主键放入where子句时，mysql把这个查询转为一个常量, 速度比较快',
            'system'          => '当确定最多只会有一行匹配的时候，MySQL优化器会在查询前读取它而且只读取一次，因此非常快。当主键放入where子句时，mysql把这个查询转为一个常量, 速度比较快。 当查询的表只有一行的情况下，使用system',
            'null'            => 'MySQL在优化过程中分解语句，执行时甚至不用访问表或索引，例如从一个索引列里选取最小值可以通过单独索引查找完成',
        ],
        'possible_keys' => '指出MySQL能使用哪个索引在表中找到记录，查询涉及到的字段上若存在索引，则该索引将被列出，但不一定被查询使用',
        'key'           => '显示MySQL实际决定使用的键（索引），必然包含在possible_keys中',
        'key_len'       => '表示索引中使用的字节数，可通过该列计算查询中使用的索引的长度（key_len显示的值为索引字段的最大可能长度，并非实际使用长度，即key_len是根据表定义计算而得，不是通过表内检索出的） 不损失精确性的情况下，长度越短越好,通过这个值可以算出具体使用了索引中的哪些列',
        'ref'           => '显示使用哪个列或常数与key一起从表中选择行',
        'rows'          => 'mysql估计要读取并检测的行数，注意这个不是结果集里的行数',
        'filtered'      => '使用explain extended时会出现这个列，5.7之后的版本默认就有这个字段，不需要使用explain extended了。这个字段表示存储引擎返回的数据在server层过滤后，剩下多少满足查询的记录数量的比例，注意是百分比，不是具体记录数',
        'Extra'         => [
            'distinct'                     => '一旦mysql找到了与行相联合匹配的行，就不再搜索了',
            'using index'                  => '这发生在对表的请求列都是同一索引的部分的时候，返回的列数据只使用了索引中的信息，而没有再去访问表中的行记录',
            'using where'                  => 'mysql服务器将在存储引擎检索行后再进行过滤。就是先读取整行数据，再按 where 条件进行检查，符合就留下，不符合就丢弃',
            'using temporary'              => 'mysql需要创建一张临时表来处理查询。出现这种情况一般是要进行优化的，首先是想到用索引来优化',
            'using filesort'               => 'mysql 会对结果使用一个外部索引排序，而不是按索引次序从表里读取行。此时mysql会根据联接类型浏览所有符合条件的记录，并保存排序关键字和行指针，然后排序关键字并按顺序检索行信息。这种情况下一般也是要考虑使用索引来优化的',
            'select tables optimized away' => '在没有GROUP BY子句的情况下，基于索引优化MIN/MAX操作，或者对于MyISAM存储引擎优化COUNT(*)操作，不必等到执行阶段再进行计算，查询执行计划生成的阶段即完成优化',
            'impossible where'             => 'where子句的值总是false，不能用来获取任何信息',
            'using join buffer'            => '使用了连接缓存：Block Nested Loop，连接算法是块嵌套循环连接;Batched Key Access，连接算法是批量索引连接',
            'using index condition'        => '这是MySQL 5.6出来的新特性，叫做“索引条件推送”。简单说一点就是MySQL原来在索引上是不能执行如like这样的操作的，但是现在可以了，这样减少了不必要的IO操作，但是只能用在二级索引上',
            'not exists'                   => 'MYSQL优化了LEFT JOIN，一旦它找到了匹配LEFT JOIN标准的行， 就不再搜索了',
            'using mrr'                    => '使用多范围读取优化策略读取表格',
            'full scan on null key'        => '当优化器不能使用索引查找访问方法时，将子查询优化作为回退策略',
            'impossible having'            => 'HAVING子句总是错误的，不能查询任何行',
            'impossible where'             => 'WHERE子句总是错误的，不能查询任何行',
            'unique row not found'         => '没有行满足表中唯一索引或主键的条件',
            'using index for group-by'     => '与using index类似，using index for group-by表明mysql找到一个索引，该索引可以返回所有group-by或distinct查询所需的列，不需要回表',
            'zero limit'                   => '该查询有一个LIMIT 0子句，不能选择任何行',
        ]
    ];

    /**
     * 获取对应的说明解释
     * @param $explainList
     * @return array
     */
    public static function getExplication($explainList)
    {
        if (empty($explainList)) {
            return [];
        }
        $remark       = self::REMARK_MAP;
        $select_types = array_unique(array_filter(array_column($explainList, 'select_type')));
        $types        = array_unique(array_filter(array_column($explainList, 'type')));
        $Extras       = array_unique(array_filter(array_column($explainList, 'Extra')));
        // var_dump($select_types, $types, $Extras);

        $explication                = [];
        $explication['select_type'] = [];
        foreach ($select_types as $select_type) {
            $explication['select_type'][] = $select_type . ': ' . $remark['select_type'][strtoupper($select_type)];
        }

        $explication['type'] = [];
        foreach ($types as $type) {
            $explication['type'][] = $type . ': ' . $remark['type'][strtolower($type)];
        }

        $explication['possible_keys'] = $remark['possible_keys'];
        $explication['key']           = $remark['key'];
        $explication['key_len']       = $remark['key_len'];
        $explication['ref']           = $remark['ref'];
        $explication['rows']          = $remark['rows'];

        if (isset($explainList[0]['filtered'])) {
            $explication['filtered'] = $remark['filtered'];
        }
        foreach ($Extras as $Extra) {
            //Using where; Using index
            foreach (explode(';', $Extra) as $ex) {
                $ex = trim($ex);
                if (isset($remark['Extra'][strtolower($ex)]) && !empty($remark['Extra'][strtolower($ex)])) {
                    $explication['Extra'][$ex] = $ex . ': ' . $remark['Extra'][strtolower($ex)];
                }
            }
        }
        $explication['Extra'] = array_values($explication['Extra'] ?? []);
        return $explication;
    }

    /**
     * 获取sql分数
     * @param $countSugs
     * @param $explainList
     * @return string
     */
    public static function getScore($countSugs, $explainList)
    {
        $types = array_unique(array_filter(array_column($explainList, 'type')));
        $score = 0;
        $i     = 0;
        if (empty($types)) {
            return 0;
        }
        foreach ($types as $type) {
            if (isset(self::SQL_SCORE_MAP[strtolower($type)])) {
                $i++;
                $score += self::SQL_SCORE_MAP[strtolower($type)];
            }
        }
        $score = number_format(($score / $i) - 0.5 * $countSugs, 1);
        if ($score < 0) {
            $score = 0;
        }
        return $score;
    }
}
