<?php 

namespace controllers;

use models\Privilege;

class PrivilegeController{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Privilege;
        $data = $model->findAll([
            'per_page' => 12,
        ]);    
      
        view('admin.istrators.privilege_rule',$data);
    }

    public function cate()
    {
        view('admin.istrators.privilege_cate');
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.privilege.create');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new Privilege;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/privilege/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Privilege;
        $data = $model->findOne($_GET['id']);
        view('admin.privilege.edit',[
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
         $model = new Privilege;
         $model->fill($_POST);
         $model->update($_GET['id']);
         redirect('/admin/privilege/index');
     }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
     public function delete()
     {
         $model = new Privilege;
         $model->delete($_GET['id']);
         redirect('/admin/privilege/index');
     }
}