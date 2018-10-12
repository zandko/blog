<?php

namespace controllers;

use models\Article;
use models\Sort;

class ArticleController extends BaseController
{   

    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Article;
        $data = $model->findAll([
            'per_page' => 14,
        ]);

        $article_sort = $model -> article_sort();

        $sort = new Sort;
        $sort_list = $sort->findAll();

        view('admin.article.article_list', [
            'data' => $data,
            'sort' => $sort_list['data'],
            'article_sort' => $article_sort,
        ]);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {   
        $model = new Sort;
        $data = $model->tree();
        view('admin.article.article_create',$data);
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Article;
        $model->fill($_POST);
        $model->insert();

        echo json_encode([
            'status' => '200',
            'messages' => '发表成功!',
        ]);
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Article;
        $data = $model->findOne($_GET['id']);
        $article_sort = $model -> article_sort();
        $sort = new Sort;
        $dataAll = $sort->tree();
        view('admin.article.article_edit', [
            'data' => $data,
            'dataAll' => $dataAll,
            'article_sort' => $article_sort,
        ]);
    }

    /**
     * 处理要修改的数据
     *
     * @access public
     */
    public function update()
    {
        $model = new Article;
        $model->fill($_POST);
        $model->update($_GET['id']);

        echo json_encode([
            'status' => '200',
            'messages' => '修改成功!',
        ]);
    }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
    public function delete()
    {
        $model = new Article;
        $model->delete($_POST['id']);

        echo json_encode([
            'status' => '200',
            'messages' => '已删除!',
        ]);
    }
}
