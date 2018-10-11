<?php

namespace controllers;

use models\Admin;
use models\Role;

class AdminController
{
    /**
     * 显示首页
     *
     * @access public
     */
    public function index()
    {
        view('admin.index.index');
    }

    public function welcome()
    {
        view('admin.index.welcome');
    }

    /**
     * 显示数据列表页
     */
    function list() {
        $model = new Admin;
        $data = $model->findAll();
        view('admin.istrators.admin_list', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        $model = new Role;
        $data = $model->findAll();
        view('admin.istrators.admin_add', $data);
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Admin;
        $model->fill($_POST);
        $model->insert();

        echo json_encode([
            'status' => '200',
            'messages' => '添加成功!',
        ]);
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Admin;
        $data = $model->findOne($_GET['id']);

        $RoleModel = new Role;
        $RoleData = $RoleModel->findAll();

        view('admin.istrators.admin_edit', [
            'data' => $data,
            'RoleData' => $RoleData,
        ]);

    }

    /**
     * 处理要修改的数据
     *
     * @access public
     */
    public function update()
    {
        $model = new Admin;
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
        $model = new Admin;
        $model->delete($_GET['id']);

        echo json_encode([
            'status' => '200',
            'messages' => '已删除!',
        ]);
    }

    public function stop()
    {
        $model = new Admin;
        $model->stop($_POST['id']);

        echo json_encode([
            'id' => $_POST['id'],
            'status' => '200',
            'messages' => '已停用!',
        ]);
    }

    public function start()
    {
        $model = new Admin;
        $model->start($_POST['id']);

        echo json_encode([
            'id' => $_POST['id'],
            'status' => '200',
            'messages' => '已启用!',
        ]);
    }
}
