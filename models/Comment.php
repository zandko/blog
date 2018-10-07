<?php 

namespace models;

class Comment extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'comments';
    protected $fillable = ['article_id','user_id','comment_content','parent_comment_id'];
}