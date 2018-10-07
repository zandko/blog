<?php

namespace libs;

use PDO;
use PDOException;

class Db
{
    private $pdo;
    private static $instance;
    private $error;
    private function __clone()
    {}
    private function __construct()
    {   
        try{
            $config = config('mysql');
            $this->pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['root'], $config['password']);
            $this->pdo->exec("SET NAMES {$config['charset']}");
        }catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
        
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
