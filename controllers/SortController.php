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
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Sort;
        $model->fill($_POST);
        $model->insert();

        $db = \libs\Db::getInstance();
        $id = $db->lastInsertId();

        $data = $model->findOne($id);
        echo json_encode($data);
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

        $dataAll = $model->findAll([
            'order_by' => 'concat(path,id,"-")',
            'order_way' => 'asc',
        ]);

        view('admin.sorts.sort_edit', [
            'data' => $data,
            'dataAll' => $dataAll,
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

        echo json_encode([
            'status' => '200',
            'messages' => '添加成功!',
        ]);
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
        echo json_encode([
            'status' => '200',
            'messages' => '删除成功!',
        ]);
    }
}
