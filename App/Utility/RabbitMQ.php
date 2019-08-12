<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/8/2
 * Time: 9:13
 */

namespace App\Utility;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Throwable;

/**
 * Class RabbitMQ
 * @package App\Utility
 */
class RabbitMQ
{
    /**
     * 连接
     * @var AMQPStreamConnection
     */
    public $connect;

    /**
     * 管道
     * @var AMQPChannel
     */
    public $channel;

    /**
     * @var string 交换机
     */
    public $exchangeName;

    /**
     * 表名
     * @var $table
     */
    public $table = [];

    /**
     * RabbitMQ constructor.
     * @param array $config
     * @param string $exchangeName
     * @param int $maxPriority
     * @throws Throwable
     */
    public function __construct(array $config = [], $exchangeName = '', $maxPriority = 10)
    {
        // 创建一个连接
        $this->connect = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password'], $config['vhost']);

        // 创建一个信道
        $this->channel = $this->connect->channel();

        // 交换机名称
        $this->exchangeName = $exchangeName;

        // table
        if ($maxPriority > 0) {
            $this->table = new AMQPTable();
            $this->table->set('x-max-priority', $maxPriority);
        }

        // 创建交换机
        $this->channel->exchange_declare($this->exchangeName, 'direct', false, true, false);
    }

    /**
     * 生产信息
     * @param mixed $message 消息
     * @param string $queueName 队列名
     * @param int $priority 优先级
     * @throws \Exception
     */
    public function set($message, $queueName = '', $priority = 0)
    {
        // 创建队列
        $this->channel->queue_declare($queueName, false, true, false, false, false, $this->table);

        // 队列绑定
        $this->channel->queue_bind($queueName, $this->exchangeName, $queueName);

        $message = is_string($message) ? $message : json_encode($message, JSON_UNESCAPED_UNICODE);
        // 构造消息体
        $properties = [
            'content_type' => 'text/plain',
            'priority' => $priority,
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ];
        $sendMessage = new AMQPMessage($message, $properties);

        $this->channel->basic_publish($sendMessage, $this->exchangeName, $queueName);
        $this->channel->close();
        $this->connect->close();
    }

    /**
     * 消费消息
     * @param bool $isAck
     * @param string $queueName
     * @return mixed
     */
    public function get($queueName = '', $isAck = false)
    {
        // 创建队列
        $this->channel->queue_declare($queueName, false, true, false, false, false, $this->table);

        // 队列绑定
        $this->channel->queue_bind($queueName, $this->exchangeName, $queueName);

        return $this->channel->basic_get($queueName, $isAck);
    }
}