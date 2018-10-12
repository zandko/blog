<?php

namespace controllers;

use models\Admin;

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
