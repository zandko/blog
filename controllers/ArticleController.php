<?php

namespace controllers;

use models\Article;

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
        $data = $model->findAll();
        view('admin.articles.article_list', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.article.article_create');
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
        view('admin.article.article_edit', [
            'data' => $data,
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
    }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
    public function delete()
    {
        $model = new Article;
        $model->delete($_GET['id']);
    }
}
