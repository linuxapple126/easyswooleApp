<?php

namespace App\HttpController\Admin;

use App\HttpController\Base;
use App\Service\AdminService;
use Throwable;

class Auth extends Base
{
    /**
     * 管理员登录
     * @throws Throwable
     */
    public function login()
    {
        if ($this->isPost()) {
            $request = $this->request()->getParsedBody();
            $response = (new AdminService())->handleLogin($request);
            $this->responseJson($response);
        }
        return $this->view('admin.auth.login');
    }

    /**
     * 管理员退出
     * @throws Throwable
     */
    public function logout()
    {
        $admin_id = $this->request()->getParsedBody('admin_id');
        $response = ((new AdminService()))->handleLogOut($admin_id);
        $this->responseJson($response);
    }
}