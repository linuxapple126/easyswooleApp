<?php

namespace App\Model;

use EasySwoole\Mysqli\Mysqli;
use Throwable;

/**
 * Class AdminModel
 * @package App\Model
 */
class AdminModel extends BaseModel
{
    /**
     * 管理员表
     * @var string
     */
    private static $table = 'admin';

    /**
     * @param $username
     * @return Mysqli|mixed
     * @throws Throwable
     */
    public function getUserByUserName($username)
    {
        return $this->mysql->where('user_name', $username)->getOne(self::$table);
    }
}
