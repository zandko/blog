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
}