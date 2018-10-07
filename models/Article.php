<?php 

namespace models;

class Article extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'articles';
    protected $fillable = ['user_id','article_title','article_content','article_views','article_comment_count','article_like_count'];
}