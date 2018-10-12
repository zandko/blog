<?php 

namespace controllers;

use models\Comment;

class CommentController extends BaseController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Comment;
        $data = $model->findAll();
        view('admin.comment.index',$data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.comment.create');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Comment;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/comment/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Comment;
        $data = $model->findOne($_GET['id']);
        view('admin.comment.edit',[
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
         $model = new Comment;
         $model->fill($_POST);
         $model->update($_GET['id']);
         redirect('/admin/comment/index');
     }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
     public function delete()
     {
         $model = new Comment;
         $model->delete($_GET['id']);
         redirect('/admin/comment/index');
     }
}