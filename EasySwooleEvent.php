<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Exception\ExceptionHandler;
use App\Middleware\CorsMiddleware;
use App\Middleware\TokenMiddleware;
use App\Process\HotReload;
use App\Process\Queue;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\MysqlPoolSlave;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Di;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 载入项目 Conf 文件夹中所有的配置文件
        self::loadConf(EASYSWOOLE_ROOT . '/config.php');
        // 异常捕捉
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [ExceptionHandler::class, 'handle']);
    }


    public static function mainServerCreate(EventRegister $register)
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
//        $server->addProcess((new Queue())->getProcess());

        /*
         * mysql redis 预加载
         */
        $register->add($register::onWorkerStart, function (\swoole_server $server, int $workerId) {
            if ($server->taskworker == false) {
                //每个worker进程都预创建连接
                PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));//最小创建数量
                PoolManager::getInstance()->getPool(RedisPool::class)->preLoad(Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));//最小创建数量

                PoolManager::getInstance()->getPool(MysqlPoolSlave::class)->preLoad(Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));//最小创建数量
            }
        });

        Di::getInstance()->set(SysConst::LOGGER_HANDLER,\App\Utility\Logger::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return bool
     * @throws \Throwable
     */
    public static function onRequest(Request $request, Response $response): bool
    {
        $cors = CorsMiddleware::getInstance()->handle($request, $response);
        if (!$cors) return false;

//        $token = TokenMiddleware::getInstance()->handle($request, $response);
//        if (!$token) return false;

        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {

    }

    /**
     * 加载自定义配置
     * @param $ConfPath
     */
    public static function loadConf($ConfPath)
    {
        $conf = Config::getInstance();
        $data = require_once $ConfPath;
        foreach ($data as $key => $val) {
            $conf->setConf((string)$key, (array)$val);
        }
    }
}
