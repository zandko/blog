<?php

namespace models;

class Sort extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'sorts';
    protected $fillable = ['sort_name', 'path', 'parent_sort_id'];

    public function getCat($parent_id = 0)
    {
        return $this->findAll([
            'where' => ' parent_sort_id=' . $parent_id,
        ]);
    }
}
