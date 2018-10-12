<?php

namespace controllers;

class BaseController
{
    public function __construct()
    {
        if (!isset($_SESSION['id'])) {
            header('Location:/login/signin');
        }

        if (isset($_SESSION['root'])) {
            return;
        }
        $path = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : 'admin/index';

        $whiteList = ['admin/index', 'admin/welcome'];

        if (!in_array($path, array_merge($whiteList, $_SESSION['url_path']))) {
            die('无权访问');
        }
    }

}
