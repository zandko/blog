<?php 

namespace models;

class Privilege extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'privilege';
    protected $fillable = ['privilege_name','url_path','parent_id'];


    protected function _before_delete()
    {
        $sql = "DELETE FROM privilege WHERE parent_id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $_POST['id'],
        ]);
    }
    
}