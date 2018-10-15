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

        $article = $articleModel->findAll([
            'per_page' => 25,
        ]);

        $logo = $articleModel->logo();

        view('front.index',[
            'display' => $display['data'],
            'sort' => $sort['data'],
            'article' => $article,
            'logo' => $logo,
        ]);
    }

    public function ajax_newblogs()
    {   
        $articleModel = new Article;
        $article = $articleModel->findAll([
            'per_page' => 9999,
        ]);

        echo json_encode([
            'article' => $article['data'],
        ]);
    }

    public function about()
    {
        view('front.about');
    }

}
