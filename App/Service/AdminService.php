<?php

namespace App\Service;

use App\Constants\ErrorConst;
use App\Model\AdminModel;
use App\Model\RoleModel;
use App\Utility\JWT;
use Throwable;

/**
 * Class AdminService
 * @package App\Service\Common
 */
class AdminService extends BaseService
{
    /**
     * @var AdminModel
     */
    private $adminModel;

    /**
     * AdminService constructor.
     */
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * 管理员登录
     * @param $request
     * @return array
     * @throws Throwable
     */
    public function handleLogin($request)
    {
        $userResult = $this->adminModel->getUserByUserName($request['username']);
        if (!$userResult) {
            return $this->fail(ErrorConst::NAME_UNDEFINED);
        }
        if (md5($request['password']) != $userResult['password']) {
            return $this->fail(ErrorConst::NAME_OR_PASSWORD);
        }
        if ($userResult['is_lock']) {
            return $this->fail(ErrorConst::USER_FORBIDDEN);
        }
        $adminRoleAct = (new RoleModel())->getRoleActByRoleId($userResult['role_id']);
        unset($userResult['password']);
        $token = (new JWT())->jwtEncode($userResult);
        $response = [
            'info' => $userResult,
            'act' => $adminRoleAct,
            'token' => $token
        ];
        $this->getRedis()->set('admin_info_' . $userResult['admin_id'], json_encode($response), 7200);
        return $this->success($response);
    }

    /**
     * 管理员退出
     * @param $admin_id
     * @return array
     * @throws Throwable
     */
    public function handleLogOut($admin_id)
    {
        if (!$admin_id) {
            return $this->fail(null, 'id不能为空');
        }
        $response = $this->getRedis()->del('admin_info_' . $admin_id);
        return $this->success($response);
    }
}
