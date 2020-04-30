<?php
/**
 * Desc:
 * User: baagee
 * Date: 2020/4/15
 * Time: 上午11:11
 */

namespace App\Library;

use BaAGee\NkNkn\Constant\CoreNoticeCode;

final class  ErrorCode extends CoreNoticeCode
{
    const SQL_RUN_ERROR       = 100100;
    const REPEAT_SQL_ERROR    = 100200;
    const CLEAR_PROJECT_ERROR = 100300;
}
