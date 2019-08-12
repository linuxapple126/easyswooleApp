<?php

namespace App\Middleware;

use App\Constants\ErrorConst;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;
use App\Utility\JWT;
use EasySwoole\Component\Singleton;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Throwable;

/**
 * Class TokenMiddleware
 * @package App\Middleware
 */
class TokenMiddleware
{
    use Singleton, ResponseTrait, RedisTrait;

    /**
     * @var array
     */
    private $except = [
        '/',
        '/juice/auth/login'
    ];

    /**
     * @param Request $request
     * @param Response $response
     * @return bool
     * @throws Throwable
     */
    public function handle(Request $request, Response $response)
    {
        $request_method = $request->getMethod();
        //所有请求url转小写
        $request_uri = strtolower($request->getUri()->getPath());
        // 判断请求为post 并且请求路由不在忽略的路由当中 则需要进行验证处理
        if ($request_method == "POST" && !in_array($request_uri, $this->except)) {
            $token = $request->getHeader('token')[0];
            if (!$token) {
                $result = $this->fail(ErrorConst::TOKEN_NOT_EMPTY);
                $this->responseJson($response, $result);
                return false;
            }
            // 验证token 是否正确
            $result = (new JWT())->jwtDecode($token);
            if (!$result['status']) {
                $result = $this->fail(ErrorConst::TOKEN_INVALID);
                $this->responseJson($response, $result);
                return false;
            }
            // 判断是否登录
            $adminId = $result['data']['data']->admin_id;
            $adminInfo = $this->getRedis()->get('admin_info_' . $adminId);
            $adminInfo = json_decode($adminInfo, true);
            if (!$adminInfo) {
                $result = $this->fail(null, '您没有登录');
                $this->responseJson($response, $result);
                return false;
            }
            // 单点登录判断
            if ($token != $adminInfo['token']) {
                $result = $this->fail(null, '该用户已在其他设备登录,请您重新登录');
                $this->responseJson($response, $result);
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * @param Response $response
     * @param $result
     * @return Response
     */
    protected function responseJson(Response $response, $result)
    {
        $response->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $response->withHeader('Content-type', 'application/json;charset=utf-8');
        $response->withStatus(Status::CODE_OK);
        $response->end();
        return $response;
    }
}