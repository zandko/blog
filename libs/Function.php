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

function strCut($str,$length)//$str为要进行截取的字符串，$length为截取长度（汉字算一个字，字母算半个字）
{
	$str = trim($str);
	$string = "";
	if(strlen($str) > $length)
	{
		for($i = 0 ; $i<$length ; $i++)
		{
			if(ord($str) > 127)
			{
				$string .= $str[$i] . $str[$i+1] . $str[$i+2];
				$i = $i + 2;
			}
			else
			{
				$string .= $str[$i];
			}
		}
		$string .= "...";
		return $string;
	}
	return $str;
}