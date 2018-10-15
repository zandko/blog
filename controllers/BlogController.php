<?php

namespace controllers;

use models\Article;
use models\Sort;
use models\Label;
use libs\Image;

class BlogController 
{   
   
    public function fengmian()
    {
        view('front.fengmian');
    }

    public function info()
    {   
        $model = new Article;
        $data = $model->findOne($_GET['id']);
        view('front.info',[
            'data' => $data,
        ]);
    }
}
