<?php

namespace controllers;

use models\Role;

class RoleController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Role;
        $data = $model->findAll([
            'fields' => ' a.*,GROUP_CONCAT(c.privilege_name) list ',
            'join' => ' a LEFT JOIN role_privilege b ON a.id = b.role_id LEFT JOIN privilege c ON b.pri_id = c.id ',
            'groupby' => ' GROUP BY a.id ',
        ]);

        view('admin.istrators.role_list', $data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.istrators.role_add');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/role/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Role;
        $data = $model->findOne($_GET['id']);
        view('admin.role.edit', [
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
        $model = new Role;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/admin/role/index');
    }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
    public function delete()
    {
        $model = new Role;
        $model->delete($_GET['id']);
        redirect('/admin/role/index');
    }
}
