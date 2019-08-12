<?php

namespace App\Service;

use App\Traits\MysqlTrait;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;

/**
 * Class BaseService
 * @package App\Service
 */
class BaseService
{
    use ResponseTrait,RedisTrait,MysqlTrait;
}
