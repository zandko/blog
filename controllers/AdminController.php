<?php

namespace controllers;

class AdminController
{
    public function index()
    {
        view('admin.index.index');
    }

    public function welcome()
    {
        view('admin.index.welcome');
    }
}
