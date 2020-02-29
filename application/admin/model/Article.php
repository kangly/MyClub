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
     * 文章列表
     * @param int $page
     * @param int $page_size
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articles_list($page=1,$page_size=5)
    {
        return $this
            ->field('id,title,summary,thumb,left(create_time,16) posted_at')
            ->where('is_publish','=',1)
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
            ->find();
    }
}