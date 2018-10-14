<?php

namespace controllers;

use models\Article;
use models\Sort;

class IndexController
{
    public function index()
    {   
        $articleModel = new Article;
        $display = $articleModel->findAll([
            'order_by' => 'article_like_count',
            'order_way' => 'DESC',
            'per_page' => 8,
        ]);

        $sortModel = new Sort;
        $sort = $sortModel->findAll([
            'where' => ' parent_id = 0',
        ]);

        view('front.index',[
            'display' => $display['data'],
            'sort' => $sort['data'],
        ]);
    }

    public function about()
    {
        view('front.about');
    }

}
