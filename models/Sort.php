<?php

namespace models;

class Sort extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'sorts';
    protected $fillable = ['sort_name',  'parent_id'];

    protected function _before_delete()
    {
        $sql = "DELETE FROM sorts WHERE parent_id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $_GET['id'],
        ]);
    }
}
