<?php

namespace models;

class Article extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'articles';
    protected $fillable = ['user_id', 'article_title', 'article_content', 'article_views', 'article_comment_count', 'article_like_count'];

    protected function _before_write()
    {
        $this->data['user_id'] = $_SESSION['id'];
    }

    protected function _after_write()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];
        $stmt = $this->_db->prepare("DELETE FROM article_sort WHERE article_id=?");
        $stmt->execute([
            $id,
        ]);

        $stmt = $this->_db->prepare("INSERT INTO article_sort(article_id,sort_id) VALUES(?,?)");
        $stmt->execute([
            $id,
            $_POST['sort_id'],
        ]);
    }

    protected function _before_delete()
    {   

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
}
