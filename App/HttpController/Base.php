<?php

namespace App\HttpController;

use App\Constants\ErrorConst;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;

/**
 * Class Common
 * @package App\HttpController
 */
abstract class Base extends Controller
{
    use RedisTrait, ResponseTrait;

    /**
     *
     */
    public function index()
    {
        $this->response()->write('hello');
    }

    /**
     * 自定义返回json
     * @param $result
     * @return bool
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-23 上午9:52
     */
    protected function responseJson($result)
    {
        if (!$this->response()->isEndResponse()) {
            $this->response()->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(Status::CODE_OK);
            $this->response()->end();
            return true;
        }
        return false;
    }

    /**
     * 是否POST请求
     * @return bool
     */
    protected function isPost()
    {
        $method = $this->request()->getMethod();
        if ($method != 'POST') {
            return false;
        }
        return true;
    }

    /**
     * 是否GET请求
     * @return bool
     */
    protected function isGet()
    {
        $method = $this->request()->getMethod();
        if ($method != 'GET') {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function isAjax(): bool
    {
        if (strtolower($this->request()->getHeaderLine('x-requested-with')) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    /**
     * 空方法
     * @param string|null $action
     */
    protected function actionNotFound(?string $action)
    {
        $this->responseJson($this->fail(ErrorConst::METHOD));
    }
}