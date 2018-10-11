<?php 

namespace models;

class Admin extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'admin';
    protected $fillable = ['username','password'];
}