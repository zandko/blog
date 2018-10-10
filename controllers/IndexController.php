<?php

namespace controllers;

class IndexController
{
    public function index()
    {
        view('front.index');
    }

    public function about()
    {
        view('front.about');
    }

}
