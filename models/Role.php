<?php 

namespace models;

class Role extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'role';
    protected $fillable = ['role_name'];
}