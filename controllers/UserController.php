<?php

namespace controllers;

use models\User;

class UserController extends BaseController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new User;
        $where = '1';   
        if (isset($_GET['keyword']) && $_GET['keyword']) {
            $where .= " AND user_email LIKE '%{$_GET['keyword']}%' ";
        }
        if (isset($_GET['start']) && $_GET['start']) {
            $where .= " AND user_created_at >= '{$_GET['start']}' ";
        }
        if (isset($_GET['end']) && $_GET['end']) {
            $where .= " AND user_created_at <= '{$_GET['end']}' ";
        }
        $data = $model->findAll([
            'per_page' => 7,
            'where' => $where,
        ]);
        view('admin.users.user_list', $data);
    }

    /**
     * 个人用户信息
     */
    public function show()
    {
        $model = new User;
        $data = $model->findOne($_GET['id']);
        view('admin.users.user_show', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.users.user_add');
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
                'errors' => '邮箱重复',
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
        view('admin.users.user_edit', [
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
        view('admin.users.user_password', [
            'data' => $data,
        ]);
    }

    /**
     * 等级管理
     */
    public function level()
    {
        view('admin.users.user_level');
    }

    /**
     * 积分管理
     */
    public function kiss()
    {
        view('admin.users.user_kiss');
    }

    /**
     * 浏览记录
     */
    public function view()
    {
        view('admin.users.user_view');
    }
    
}
