<?php 

namespace models;

class Label extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'labels';
    protected $fillable = ['label_name','label_description'];

    protected function _before_delete()
    {
        $stmt = $this->_db->prepare("DELETE FROM set_article_label WHERE label_id = ?");
        $stmt->execute([
            $_POST['id'],
        ]);
    }

    public function delAll()
    {   
        if(isset($_POST['noList'])){

            $stmt = $this->_db->prepare("DELETE FROM set_article_label WHERE label_id=?");
        
            $keys = [];
            $values = [];
            foreach ($_POST['noList'] as $v) {
                $keys[] = '?';
                $values[] = $v;
                $stmt->execute([
                    $v,
                ]);
            }
            $keys = implode(',', $keys);
            $sql = "DELETE FROM labels WHERE id in($keys)";
            $stmt = $this->_db->prepare($sql);
            return $stmt->execute($values);
        }else {
            return false;
        }
    }
}