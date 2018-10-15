<?php

namespace models;

use libs\Image;

class Article extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'articles';
    protected $fillable = ['user_id', 'article_title', 'article_content', 'article_views', 'article_comment_count', 'article_like_count','logoType','logo'];

    public function logo()
    {
        $stmt = $this->_db->prepare("SELECT * FROM article_logo");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function _before_write()
    {
        $this->data['user_id'] = $_SESSION['id'];
    }

    protected function _after_write()
    {       
        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];
        
        if(!empty($_FILES)) {
            $stmt = $this->_db->prepare("INSERT INTO article_logo(article_id,logo) VALUES(?,?)");
       
            $tmp_name = [];

            foreach($_FILES['logo']['name'] as $k => $v) {

                $tmp_name['name'] = $v;
                $tmp_name['error'] = $_FILES['logo']['error'][$k];
                $tmp_name['size'] = $_FILES['logo']['size'][$k];
                $tmp_name['tmp_name'] = $_FILES['logo']['tmp_name'][$k];
                $tmp_name['type'] = $_FILES['logo']['type'][$k];

                $_FILES['tmp'] = $tmp_name;

                $file = $_FILES['tmp'];
                
                $image = new Image($file);
                
                // if($_POST['logoType']=='大') {

                // }else {
                    $image->thumb = array(
                        'is_thumb' => 1,
                        'width' => 640,
                        'height' => 426,
                    );
                // }
                
                
                $ret = $image->save('/home/zan/project/Practice/public/uploads/article/thum/');
                $_ret = substr($ret, strpos($ret, '/') + 33);
                $stmt->execute([
                    $id,
                    $_ret,
                ]);
            }
        }
        

        $stmt = $this->_db->prepare("DELETE FROM article_sort WHERE article_id=?");
        $stmt->execute([
            $id,
        ]);

        $stmt = $this->_db->prepare("DELETE FROM set_article_label WHERE article_id=?");
        $stmt->execute([
            $id,
        ]);

        $stmt = $this->_db->prepare("INSERT INTO article_sort(article_id,sort_id) VALUES(?,?)");
        $stmt->execute([
            $id,
            $_POST['sort_id'],
        ]);

        $stmt = $this->_db->prepare("INSERT INTO set_article_label(article_id,label_id) VALUES(?,?)");
      
        if(isset($_POST['noList'])){
            
            foreach($_POST['noList'] as $v) {
                $stmt->execute([
                    $id,
                    $v,
                ]);
            }
        }
    }

    protected function _before_delete()
    {   
        $stmt = $this->_db->prepare("DELETE FROM set_article_label WHERE article_id=?");
        $stmt->execute([
            $_POST['id'],
        ]);
        $stmt = $this->_db->prepare("DELETE FROM article_sort WHERE article_id=?");
        $stmt->execute([
            $_POST['id'],
        ]);
    }

    public function article_sort()
    {
        $stmt = $this->_db->prepare("SELECT * FROM article_sort");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delAll()
    {   
        if(isset($_POST['noList'])){

            $stmt = $this->_db->prepare("DELETE FROM article_sort WHERE article_id=?");
            $stmts = $this->_db->prepare("DELETE FROM set_article_label WHERE article_id=?");
            $keys = [];
            $values = [];
            foreach ($_POST['noList'] as $v) {
                $keys[] = '?';
                $values[] = $v;
                $stmt->execute([
                    $v,
                ]);
                $stmts->execute([
                    $v,
                ]);
            }
            $keys = implode(',', $keys);
            $sql = "DELETE FROM articles WHERE id in($keys)";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute($values);
        }else {
            return false;
        }
    }
}
