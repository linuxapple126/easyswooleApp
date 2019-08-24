<?php
/**
 * Mysql工具类
 * @category Category
 * @author qap <qiuapeng921@163.com>
 * @Date 19-5-17 下午4:20
 */

namespace App\Traits;

use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use Swoole\Coroutine;
use Throwable;

trait MysqlTrait
{
    protected $mysqlPool = MysqlPool::class;

    /**
     * Mysql初始化
     * @param null $mysqlPool
     * @return MysqlObject
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     * @throws Throwable
     */
    protected function getMysql($mysqlPool = null): MysqlObject
    {
        $key = md5(static::class);
        $obj = ContextManager::getInstance()->get($key);
        if ($obj) return $obj;
        if($mysqlPool){
            $this->mysqlPool = $mysqlPool;
        }
        $pool = PoolManager::getInstance()->getPool($this->mysqlPool);
        if ($pool instanceof AbstractPool) {
            $obj = $pool->getObj(Config::getInstance()->getConf('MYSQL.POOL_TIME_OUT'));
            if ($obj) {
                Coroutine::defer(function () use ($pool, $obj) {
                    $pool->recycleObj($obj);
                });
                ContextManager::getInstance()->set($key, $obj);
                return $obj;
            } else {
                throw new PoolEmpty($this->mysqlPool . " pool is empty");
            }
        } else {
            throw new PoolException($this->mysqlPool . " convert to pool error");
        }
    }
}
