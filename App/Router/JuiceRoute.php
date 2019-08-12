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

class JuiceRoute
{
    use Singleton;

    public function setRoute(RouteCollector $route)
    {
        //登录退出
        $route->post('/auth/login', '/Juice/Auth/login');
        $route->post('/auth/logout', '/Juice/Auth/logout');

        //商品
        $route->post('/goods/index', '/Juice/Goods/index');
        $route->post('/goods/category', '/Juice/Goods/category');
        $route->post('/goods/pay', '/Juice/Goods/pay');

        //用户信息
        $route->post('/user/search', '/Juice/User/search');
        $route->post('/user/recharge', '/Juice/User/recharge');
    }
}