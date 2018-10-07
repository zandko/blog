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
}