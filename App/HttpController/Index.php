<?php

namespace App\HttpController;

use App\Utility\RabbitMQ;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{
    public function index()
    {
        $queueConfig = Config::getInstance()->getConf("RABBIT");
        // 队列对象
        try {
            $str = rand(00000, 99999);
            $queue = new RabbitMQ($queueConfig, 'queue');
            $queue->set($str, 'test');
            $html = file_get_contents(EASYSWOOLE_ROOT . '/Public/index.html');
            $this->response()->withHeader('Content-type', 'text/html;charset=UTF-8');
            return $this->response()->write($html);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}