<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/8/2
 * Time: 16:12
 */

namespace App\Process;

use App\Utility\Logger;
use App\Utility\RabbitMQ;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Config;

class Queue extends AbstractProcess
{
    /**
     * @param $arg
     * @throws \Throwable
     */
    public function run($arg)
    {
        $queueConfig = Config::getInstance()->getConf("RABBIT");
        // 队列对象
        $queue = new RabbitMQ($queueConfig, 'queue');
        try {
            while (true) {
                $message = $queue->get('test');
                if (!empty($message)) {
                    $body = is_string($message->body) ? $message->body : json_decode($message->body, true);
                    dd($body);
                    // 消费数据
                    //$queue->channel->basic_ack($message->delivery_info['delivery_tag']);
                }
                sleep(1);
            }
        } catch (\Exception $e) {
            Logger::getInstance()->log(json_encode($e->getMessage()));
        } finally {
            //关闭队列相关连接
            $queue->channel->close();
            $queue->connect->close();
        }
    }
}
