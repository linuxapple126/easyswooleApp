<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/7/24
 * Time: 17:45
 */

namespace App\Router;

use EasySwoole\Component\Singleton;
use FastRoute\RouteCollector;

class AdminRoute
{
    use Singleton;

    public function setRoute(RouteCollector $route)
    {
        //登录退出
        $route->post('/auth/login', '/Admin/Auth/login');
        $route->post('/auth/logout', '/Admin/Auth/logout');
    }
}