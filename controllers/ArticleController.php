<?php

namespace controllers;

use models\Article;

class ArticleController
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
        view('admin.article.index', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.article.create');
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
        redirect('/admin/article/index');
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
        view('admin.article.edit', [
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
        redirect('/admin/article/index');
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
        redirect('/admin/article/index');
    }
}
