<?php


namespace App\Middleware;

use EasySwoole\Component\Singleton;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class CorsMiddleware
{
    use Singleton;

    public function handle(Request $request, Response $response)
    {
        $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080');
        $response->withHeader('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type,Content-Length,Authorization, Accept,X-Requested-With,token,Keep-Alive');
        if ($request->getMethod() === 'OPTIONS') {
            $response->withStatus(Status::CODE_OK);
            return false;
        }
        return true;
    }
}