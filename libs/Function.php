<?php

function autoload($class)
{
    $path = str_replace('\\', '/', $class);
    require ROOT . $path . '.php';
}

spl_autoload_register('autoload');

require ROOT . 'vendor/autoload.php';

function route()
{
    if (php_sapi_name() == 'cli') {
        $controller = ucfirst($argv[1]) . 'Controller';
        $action = $argv[2];
    } else {
        if (isset($_SERVER['PATH_INFO'])) {
            $pathInfo = explode('/', $_SERVER['PATH_INFO']);
            $controller = ucfirst($pathInfo[1]) . 'Controller';
            $action = $pathInfo[2];
        } else {
            $controller = 'IndexController';
            $action = 'index';
        }
    }

    $fillController = "controllers\\" . $controller;

    $_C = new $fillController;
    $_C->$action();
}

route();

function view($file, $data = [])
{
    extract($data);
    $fileName = str_replace('.', '/', $file);
    require ROOT . 'views/' . $fileName . '.html';
}

function config($name)
{
    static $config = null;
    if ($config == null) {
        $config = require ROOT . 'config.php';
    }
    return $config[$name];
}
