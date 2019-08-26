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
        $route->get('index', '/Admin/Index/index');
        //登录退出
        $route->addRoute(['GET', 'POST'], 'auth/login', '/Admin/Auth/login');
        $route->addRoute(['GET', 'POST'], 'auth/logout', '/Admin/Auth/logout');
    }
}