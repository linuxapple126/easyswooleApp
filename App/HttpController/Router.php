<?php

namespace App\HttpController;

use App\Router\JuiceRoute;
use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

/**
 * Class Router
 * @package App\HttpController
 */
class Router extends AbstractRouter
{
    /**
     * 初始化
     * @param RouteCollector $route
     */
    public function initialize(RouteCollector $route)
    {
        $route->get('/', '/Index/index');
        // 开启全局路由(只有定义的地址才可以访问)
        $this->setGlobalMode(true);
        // 水吧路由
        $route->addGroup('/juice', function (RouteCollector $route) {
            JuiceRoute::getInstance()->setRoute($route);
        });

        // 空方法
        $this->setMethodNotAllowCallBack(function (Request $request, Response $response) {
            $response->withHeader('Content-type', 'text/html;charset=UTF-8');
            $response->write('未找到处理方法');
            $response->end();
        });
        // 空路由
        $this->setRouterNotFoundCallBack(function (Request $request, Response $response) {
            $response->withHeader('Content-type', 'text/html;charset=UTF-8');
            $response->write('未找到路由匹配');
            $response->end();
        });
    }
}
