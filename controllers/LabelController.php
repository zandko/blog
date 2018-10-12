<?php 

namespace controllers;

use models\Label;

class LabelController extends BaseController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Label;
        $data = $model->findAll();
        view('admin.label.index',$data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.label.create');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Label;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/label/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Label;
        $data = $model->findOne($_GET['id']);
        view('admin.label.edit',[
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
         $model = new Label;
         $model->fill($_POST);
         $model->update($_GET['id']);
         redirect('/admin/label/index');
     }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
     public function delete()
     {
         $model = new Label;
         $model->delete($_GET['id']);
         redirect('/admin/label/index');
     }
}