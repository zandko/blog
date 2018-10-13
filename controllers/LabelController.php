<?php 

namespace controllers;

use models\Label;
use models\Sort;

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
        $data = $model->findAll([
            'per_page' => 14,
        ]);
        view('admin.label.label_list',$data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.label.label_add');
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
    
        $_POST['id'] = $label;
        echo json_encode([
            'status' => '200',
            'messages' => '修改成功!',
            'label' => $_POST,
        ]);
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
        view('admin.label.label_edit',[
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
         $model = new Label;
         $model->delete($_POST['id']);

         echo json_encode([
             'status' => '200',
             'messages' => '已删除!',
         ]);
     }


     /**
     * 批量处理要删除的数据
     *
     * @access public
     */
    public function delAll()
    {
        $model = new Label;
        $ret = $model->delAll();
        
        if($ret == true) {
            echo json_encode([
                'status' => '200',
                'messages' => '删除成功!',
            ]);
        }else {
            echo json_encode([
                'status' => '404',
                'errormsg' => '删除失败!',
            ]);
        }
       
    }
}