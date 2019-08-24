<?php

namespace App\Model;

use App\Traits\MysqlTrait;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\Config;
use Throwable;

/**
 * Class BaseModel
 * @package App\Model
 */
class BaseModel
{
    use MysqlTrait;

    /**
     * @var MysqlObject
     */
    protected $mysql;

    /**
     * BaseModel constructor.
     * @param null $mysqlObject mysql对象
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     * @throws Throwable
     */
    public function __construct($mysqlObject = null)
    {
        if ($mysqlObject) {
            $this->mysql = $mysqlObject;
        } else {
            $this->mysql = $this->getMysql();
        }
    }

    /**
     * @param MysqlPool|null $mysqlPool
     * @return MysqlObject
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     * @throws Throwable
     */
    public function mysql($mysqlPool = null)
    {
        if ($mysqlPool) {
            return $this->getMysql($mysqlPool);
        } else {
            return $this->mysql;
        }
    }

    /**
     * @throws Throwable
     */
    public function __destruct()
    {
        if (Config::getInstance()->getConf('DEBUG')) {
            echo $this->mysql->getLastQuery() . PHP_EOL;
        }
    }
}