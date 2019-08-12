<?php

namespace App\Model;

use App\Traits\MysqlTrait;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;

/**
 * Class BaseModel
 * @package App\Model
 */
class BaseModel
{
    use MysqlTrait, RedisTrait, ResponseTrait;

    /**
     * @throws \Throwable
     */
    public function __destruct()
    {
        print_r($this->getMysql()->getLastQuery() . PHP_EOL);
    }
}