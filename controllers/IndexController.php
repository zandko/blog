<?php

namespace controllers;

use libs\Template;

class IndexController
{
    public function index()
    {
        view('front.index');
    }

    public function admin()
    {
        view('admin.index.index');
    }
}
