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

    public function show()
    {   
        $model = new User;
        $data = $model -> findOne($_GET['id']);
        view('admin.user.user_show',$data);
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
      
        echo $model->insert();        
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
