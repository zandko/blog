<?php 

namespace controllers;

use models\Privilege;

class PrivilegeController extends BaseController
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Privilege;
        $data = $model->tree();     
      
        view('admin.istrators.privilege_rule',$data);
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

        echo json_encode([
            'data' => $_POST,
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
        $model = new Privilege;
        $data = $model->findOne($_GET['id']);
        $dataAll = $model->tree();
        view('admin.istrators.privilege_edit',[
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
         $model = new Privilege;
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
         $model = new Privilege;
         $model->delete($_POST['id']);

         echo json_encode([
             'status' => '200',
             'messages' => '已删除!',
         ]);
     }
}