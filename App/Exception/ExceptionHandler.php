<?php

/**
 * 异常处理
 */

namespace App\Exception;

use App\Utility\DingDing;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Http\Message\Status;
use Throwable;

class ExceptionHandler
{
    /**
     * Description
     * @param Throwable $exception
     * @param Request $request
     * @param Response $response
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-21 上午9:14
     */
    public static function handle(Throwable $exception, Request $request, Response $response)
    {
        $uri = $request->getUri();
        $host = $uri->getHost();
        $path = $uri->getPath();
        $url = $host . $path;
        $msg = "- 请求地址：**{$url}** \n";
        $msg .= "- 文件名：**{$exception->getFile()}** \n";
        $msg .= "- 第几行：**{$exception->getLine()}** \n";
        $msg .= "- 错误信息：**{$exception->getMessage()}**\n";
        print_r($msg);
        (new DingDing())->sendMarkdown('异常错误', $msg);
        $response->withStatus(Status::CODE_GATEWAY_TIMEOUT);
        $response->withHeader('Content-type', 'application/json;charset=utf-8');
        $response->write('系统繁忙,请稍后再试');
    }
}
