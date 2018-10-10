<?php

namespace controllers;

use models\Sort;

class SortController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Sort;
        $data = $model->findAll([
            'order_by' => 'concat(path,id,"-")',
            'order_way' => 'asc',
            'per_page' => 100,
        ]);

        view('admin.sorts.sort_list', $data);
    }

    public function ajax_get_cat()
    {
        $id = (int) $_GET['id'];
        $model = new Sort;
        $data = $model->getCat($id);

        echo json_encode($data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.sort.create');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Sort;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/sort/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Sort;
        $data = $model->findOne($_GET['id']);
        view('admin.sorts.sort_edit', [
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
        $model = new Sort;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/admin/sort/index');
    }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
    public function delete()
    {
        $model = new Sort;
        $model->delete($_GET['id']);
        redirect('/admin/sort/index');
    }
}
