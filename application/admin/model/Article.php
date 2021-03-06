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
    /**
     * @return array|\think\model\relation\BelongsTo
     */
    public function column()
    {
        return $this->belongsTo('ArticleColumn','column_id','id')->field('title');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function visits()
    {
        return $this->hasMany('ArticleVisit','article_id','id');
    }

    /**
     * 文章栏目-文章 一对多关联
     * @return \think\model\relation\HasMany
     */
    public function articles()
    {
        return $this->hasMany('Article','column_id','id');
    }

    /**
     * 文章列表
     * @param array $map
     * @param int $page
     * @param int $page_size
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articles_list($map=[],$page=1,$page_size=5)
    {
        $map[] = ['is_publish','=',1];

        return $this
            ->field('id,title,summary,thumb,left(create_time,16) posted_at')
            ->where($map)
            ->withCount(['visits'=>'views'])
            ->order('id desc')
            ->page($page,$page_size)
            ->select();
    }

    /**
     * 文章详情
     * @param $id
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function article_view($id)
    {
        return $this
            ->field('id,title,content,username author,left(create_time,16) posted_at')
            ->where('id','=',$id)
            ->withCount(['visits'=>'views'])
            ->find();
    }
}