<?php

namespace libs;

class Redis
{
    private static $redis;
    private function __clone()
    {}
    private function __construct()
    {}

    public static function getInstance()
    {
        if (!self::$redis instanceof self) {
            $config = config('redis');
            self::$redis = new \Predis\Client([
                'scheme' => $config['scheme'],
                'host' => $config['host'],
                'port' => $config['port'],
            ]);
        }

        return self::$redis;
    }
}
