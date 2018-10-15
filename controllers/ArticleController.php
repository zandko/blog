<?php

namespace controllers;

use models\Article;
use models\Sort;
use models\Label;
use libs\Image;

class ArticleController extends BaseController
{   
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new Article;
        $where = '1'; 
  
        if (isset($_GET['keyword']) && $_GET['keyword']) {
            $where .= " AND (article_title LIKE '%{$_GET['keyword']}%' OR article_content LIKE '%{$_GET['keyword']}%') ";
        }
        if (isset($_GET['start']) && $_GET['start']) {
            $where .= " AND created_at >= '{$_GET['start']}' ";
        }   
        if (isset($_GET['end']) && $_GET['end']) {
            $where .= " AND created_at <= '{$_GET['end']}' ";
        }

        $data = $model->findAll([
            'fields' => ' a.*,group_concat(c.label_name) label_name',
            'join' => ' a left join set_article_label b on a.id = b.article_id left join labels c on b.label_id = c.id',
            'groupby' => 'group by a.id',
            'per_page' => 12,
            'where' => $where,
        ]);

        $article_sort = $model -> article_sort();

        $sort = new Sort;
        $sort_list = $sort->findAll();

        view('admin.article.article_list', [
            'data' => $data,
            'sort' => $sort_list['data'],
            'article_sort' => $article_sort,
        ]);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {   
        $model = new Sort;
        $data = $model->tree();
        $labelModel = new Label;
        $label = $labelModel->findAll();
        view('admin.article.article_create',[
            'data' => $data,
            'label' => $label['data'],
        ]);
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {   
        $model = new Article;
        $model->fill($_POST);
        $model->insert();
       
        echo json_encode([
            'status' => '200',
            'messages' => '发表成功!',
        ]);
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new Article;
        $data = $model->findOne($_GET['id']);
        $article_sort = $model -> article_sort();
        $sort = new Sort;
        $dataAll = $sort->tree();

        $labelModel = new Label;
        $label = $labelModel->findAll();

        $logo = $model->logo();
        // var_dump($logo);
        view('admin.article.article_edit', [
            'data' => $data,
            'dataAll' => $dataAll,
            'article_sort' => $article_sort,
            'label' => $label['data'],
            'logo' => $logo,
        ]);
    }

    /**
     * 处理要修改的数据
     *
     * @access public
     */
    public function update()
    {   
      
        $model = new Article;
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
        $model = new Article;
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
        $model = new Article;
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
