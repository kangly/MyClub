<?php

namespace app\admin\model;

use think\Model;

class Member extends Model
{
    public function articles()
    {
        return $this->belongsToMany('Article','article_store','article_id')->field('article.id,article.title,article.username author');
    }
}