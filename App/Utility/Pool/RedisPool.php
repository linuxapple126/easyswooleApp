<?php

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/19 18:19
 */
class RedisPool extends AbstractPool
{
    /**
     *
     * @return RedisObject|null
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 18:19
     */
    protected function createObject()
    {
        $redis = new RedisObject();
        $conf  = Config::getInstance()->getConf('REDIS');
        if ($redis->connect($conf['host'],$conf['port'])) {
            if (!empty($conf['auth'])) {
                $redis->auth($conf['auth']);
            }
            return $redis;
        } else {
            return null;
        }
    }
}