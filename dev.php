<?php

return [
    'SERVER_NAME' => "es-server",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER,
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,//运行的  worker进程数量
            'max_request' => 5000,// worker 完成该数量的请求后将退出，防止内存溢出
            'task_worker_num' => 8,//运行的 task_worker 进程数量
            'task_max_request' => 1000,// task_worker 完成该数量的请求后将退出，防止内存溢出
            'reload_async' => true,
            'task_enable_coroutine' => true,
            'document_root' => EASYSWOOLE_ROOT . '/Public',  // 静态资源目录
            'enable_static_handler' => true,
        ],
    ],
    'TEMP_DIR' => EASYSWOOLE_ROOT . '/Runtime/Temp',
    'LOG_DIR' => EASYSWOOLE_ROOT . '/Runtime/Log',
    'CONSOLE' => [
        'ENABLE' => true,
        'LISTEN_ADDRESS' => '127.0.0.1',
        'PORT' => 9500,
        'USER' => 'root',
        'PASSWORD' => '123456'
    ],
    'FAST_CACHE' => [
        'PROCESS_NUM' => 0,
        'BACKLOG' => 256,
    ],
    'DISPLAY_ERROR' => true,
    'PHAR' => [
        'EXCLUDE' => ['.idea', 'Log', 'Temp', 'easyswoole', 'easyswoole.install']
    ]
];
