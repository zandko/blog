<?php

namespace controllers;

use models\User;

class UserController
{
    public function register()
    {
        view('front.login');
    }

    public function login()
    {
        $model = new User;
        $model->login();
    }
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new User;
        $data = $model->findAll();
        view('admin.user.index', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.user.create');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new User;
        $model->register();
        die;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/user/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new User;
        $data = $model->findOne($_GET['id']);
        view('admin.user.edit', [
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
        $model = new User;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/admin/user/index');
    }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
    public function delete()
    {
        $model = new User;
        $model->delete($_GET['id']);
        redirect('/admin/user/index');
    }
}
