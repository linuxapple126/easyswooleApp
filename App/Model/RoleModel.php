<?php

namespace App\Model;

use EasySwoole\Mysqli\Mysqli;
use Throwable;

/**
 * Class RoleModel
 * @package App\Model
 */
class RoleModel extends BaseModel
{
    /**
     * 管理员角色表
     * @var string
     */
    private static $table = 'rd_admin_role';

    /**
     * @param $role_id
     * @return Mysqli|mixed
     * @throws Throwable
     */
    public function getRoleActByRoleId($role_id)
    {
        return $this->mysql->where('role_id', $role_id)->get(self::$table, null);
    }
}