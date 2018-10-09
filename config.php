<?php

return [
    'mode' => 'dev', //dev 开发模式   pro 上线模式
    'redis' => [
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
    ],
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'zane_blog',
        'user' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],
    'email' => [
        'mode' => 'debug', // debug: 调试模式  . production: 生产模式
        'port' => 25,
        'host' => 'smtp.163.com',
        'name' => 'push_over@163.com',
        'pass' => 'guomiaomiao521',
        'from_email' => 'push_over@163.com',
        'from_name' => '测试专用',
    ],
];
