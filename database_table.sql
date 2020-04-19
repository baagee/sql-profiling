CREATE TABLE `sql_detail`
(
    `id`       bigint(20) unsigned                    NOT NULL AUTO_INCREMENT,
    `s_id`     bigint(20) unsigned                    NOT NULL DEFAULT '0' COMMENT '主键',
    `l_id`     bigint(20) unsigned                    NOT NULL DEFAULT '0' COMMENT '请求ID',
    `query_id` int(10) unsigned                       NOT NULL DEFAULT '0' COMMENT '本次请求ID',
    `cost`     varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '总耗时',
    `sql`      longtext COLLATE utf8mb4_unicode_ci COMMENT 'sql',
    `profile`  longtext COLLATE utf8mb4_unicode_ci COMMENT 'sql profiling 详情',
    `explain`  longtext COLLATE utf8mb4_unicode_ci COMMENT 'sql执行计划',
    PRIMARY KEY (`id`),
    UNIQUE KEY `sql_detail_s_id_uindex` (`s_id`),
    KEY `sql_detail_l_id_index` (`l_id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci COMMENT ='sql运行详情表';

CREATE TABLE `requests`
(
    `id`            bigint(20) unsigned                     NOT NULL AUTO_INCREMENT,
    `l_id`          bigint(20) unsigned                     NOT NULL DEFAULT '0' COMMENT '主键',
    `x_id`          bigint(20) unsigned                     NOT NULL DEFAULT '0' COMMENT '项目-模块ID',
    `trace_id`      varchar(50) COLLATE utf8mb4_unicode_ci  NOT NULL DEFAULT '' COMMENT '追踪ID',
    `url`           varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `request_time`  timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '请求时间',
    `all_cost_time` varchar(50) COLLATE utf8mb4_unicode_ci  NOT NULL DEFAULT '' COMMENT '本次运行所有sql运行时间',
    `sql_count`     int(10) unsigned                        NOT NULL DEFAULT '0' COMMENT 'sql个数',
    `create_time`   timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `table_namerequests_l_id_uindex` (`l_id`),
    UNIQUE KEY `requests_id_uindex` (`id`),
    KEY `table_namerequests_trace_id_index` (`trace_id`),
    KEY `table_namerequests_x_id_index` (`x_id`),
    KEY `requests_request_time_index` (`request_time`),
    KEY `requests_create_time_index` (`create_time`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci COMMENT ='请求信息表';

CREATE TABLE `project_module`
(
    `id`          bigint(20) unsigned                    NOT NULL AUTO_INCREMENT,
    `x_id`        bigint(20) unsigned                    NOT NULL DEFAULT '0' COMMENT '主键',
    `project`     varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '项目',
    `module`      varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模块',
    `create_time` timestamp                              NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `project_module_x_id_uindex` (`x_id`),
    UNIQUE KEY `project_module_project_module_uindex` (`project`, `module`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci COMMENT ='项目-模块关系表';

CREATE TABLE `online_sql`
(
    `id`          bigint(20) unsigned                    NOT NULL AUTO_INCREMENT,
    `s_id`        bigint(20) unsigned                    NOT NULL DEFAULT '0' COMMENT '主键',
    `query_id`    int(10) unsigned                       NOT NULL DEFAULT '0' COMMENT '本次请求ID',
    `cost`        varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '总耗时',
    `hash`        varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'hash 用来去重',
    `sql`         longtext COLLATE utf8mb4_unicode_ci COMMENT 'sql',
    `profile`     longtext COLLATE utf8mb4_unicode_ci COMMENT 'sql profiling 详情',
    `explain`     longtext COLLATE utf8mb4_unicode_ci COMMENT 'sql执行计划',
    `create_time` timestamp                              NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `sql_detail_s_id_uindex` (`s_id`),
    KEY `online_sql_hash_index` (`hash`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci COMMENT ='在线sql运行详情表'