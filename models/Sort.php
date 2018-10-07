<?php 

namespace models;

class Sort extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'sorts';
    protected $fillable = ['sort_name','sort_description','parent_sort_id'];
}