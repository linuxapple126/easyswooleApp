<?php

namespace App\HttpController;

use App\Utility\RabbitMQ;
use EasySwoole\EasySwoole\Config;

class Index extends Base
{
    public function index()
    {
        return $this->view('index', ['demo' => '欢迎使用easyApp']);
    }

    public function queue()
    {
        $queueConfig = Config::getInstance()->getConf("RABBIT");
        //队列对象
        try {
            $str = rand(00000, 99999);
            $queue = new RabbitMQ($queueConfig, 'queue');
            $queue->set($str, 'test');
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}