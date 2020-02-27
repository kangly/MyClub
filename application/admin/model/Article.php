<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/5
 * Time: 14:31
 */
namespace app\admin\model;

use think\Model;

class Article extends Model
{
    public function column()
    {
        return $this->belongsTo('ArticleColumn','column_id','id')->field('title');
    }
}