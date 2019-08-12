<?php

namespace App\Constants;

/**
 * Class ErrorConst
 * @package App\Constants
 */
class ErrorConst
{

    const DEFAULT = 100;

    const CREATE = 10110;

    const DELETE = 10120;

    const UPDATE = 10130;

    const EDIT = 10140;

    const NOT_FOUND = 10150;

    const PARAMETER = 10160;

    const METHOD = 10170;

    const TOKEN_NOT_EMPTY = 10180;

    const TOKEN_INVALID = 10181;

    const NAME_UNDEFINED = 10190;

    const NAME_OR_PASSWORD = 10191;

    const USER_FORBIDDEN = 10200;

    const ROUTER_EMPTY = 10210;

    const PERMISSION_REFUSE = 10220;

    const PRIMARY_KEY_EMPTY = 10230;

    /**
     * @var array
     */
    private static $_MESSAGE = [
        100 => '失败',
        10110 => '添加失败',
        10120 => '删除失败',
        10130 => '更新失败',
        10140 => '编辑失败',
        10150 => '暂无记录',
        10160 => '参数错误',
        10170 => '请求方式错误',
        10180 => 'token不能为空',
        10181 => 'token无效',
        10190 => '用户名不存在',
        10191 => '用户名或密码错误',
        10200 => '用户被禁用',
        10210 => '路由地址为空',
        10220 => '暂无权限',
        10230 => '数据主键为空',
    ];

    /**
     * @param int $code
     * @return mixed|string
     */
    public static function getMessage($code = 0)
    {
        if (isset(self::$_MESSAGE[$code])) {
            return isset(self::$_MESSAGE[$code]) ? self::$_MESSAGE[$code] : '';
        }

        return '失败';
    }
}
