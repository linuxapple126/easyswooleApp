<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/7/30
 * Time: 16:40
 */

namespace App\Repository;


use App\Traits\MysqlTrait;
use App\Traits\RedisTrait;

class BaseRepository
{
    use RedisTrait, MysqlTrait;
}