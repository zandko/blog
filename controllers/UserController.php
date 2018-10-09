<?php

namespace controllers;

use models\User;

class UserController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new User;
        $data = $model->findAll([
            'per_page' => 8,
        ]);
        view('admin.user.user_list', $data);
    }

    /**
     * 个人用户信息
     */
    public function show()
    {
        $model = new User;
        $data = $model->findOne($_GET['id']);
        view('admin.user.user_show', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.user.user_add');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new User;
        $model->fill($_POST);

        $ret = $model->create();

        if ($ret != false) {
            echo json_encode([
                'status' => '404',
                'errors' => '添加失败',
            ]);
        } else {
            $model->insert();
            echo json_encode([
                'status' => '200',
                'messages' => '添加成功',
            ]);
        }

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
        view('admin.user.user_edit', [
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
        $model = new User;
        $model->delete($_GET['id']);
        echo json_encode([
            'status' => '200',
            'messages' => '删除成功!',
        ]);
    }

    /**
     * 批量删除
     */
    public function delAll()
    {
        $model = new User;
        $ret = $model->delAll();

        if ($ret != false) {
            echo json_encode([
                'status' => '200',
                'messages' => '批量删除成功!',
            ]);
        } else {
            echo json_encode([
                'status' => '404',
                'errors' => '未选择任何用户!',
            ]);
        }
    }

    /**
     * 修改用户密码
     */
    public function password()
    {
        $model = new User;
        $data = $model->findOne($_GET['id']);
        view('admin.user.user_password', [
            'data' => $data,
        ]);
    }
}
