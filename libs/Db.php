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
            $config = config('db');
            $this->pdo = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
            $this->pdo->exec("SET NAMES " . $config['charset']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
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
