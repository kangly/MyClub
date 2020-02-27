<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/8
 * Time: 10:37
 */
namespace app\admin\model;

use think\Model;

class ArticleColumn extends Model
{
    /**
     * 获取文章栏目列表
     * @param array $map
     * @param string $fields
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getColumns($map=[],$fields='*')
    {
        $rules = $this
            ->field($fields)
            ->where($map)
            ->order('id','asc')
            ->select();

        return $rules;
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
     * 根据文章栏目id查询所属文章列表
     * @param int $column_id
     * @param int $page
     * @param int $page_size
     * @return mixed
     */
    public function articles_list($column_id=0,$page=1,$page_size=5)
    {
        $column = $this->get($column_id);
        if($column){
            return $column
                ->articles()
                ->field('id,title,summary,thumb,left(create_time,16) posted_at')
                ->page($page,$page_size)
                ->order('id desc')
                ->select();
        }
        return [];
    }
}