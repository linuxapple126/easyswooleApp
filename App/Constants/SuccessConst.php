<?php

namespace App\Constants;

/**
 * Class SuccessConst
 * @package App\Constants
 */
class SuccessConst
{

    const DEFAULT = 200;

    const CREATE = 20110;

    const DELETE = 20120;

    const UPDATE = 20130;

    const EDIT = 20140;

    /**
     * @var array
     */
    private static $_MESSAGE = [
        200 => '成功',
        20110 => '添加成功',
        20120 => '删除成功',
        20130 => '更新成功',
        20140 => '编辑成功',
    ];

    /**
     * @param int $code
     * @return mixed|string
     */
    public static function getMessage($code = 0)
    {
        if (isset(self::$_MESSAGE[$code])) {
            return self::$_MESSAGE[$code];
        }

        return '成功';
    }
}
