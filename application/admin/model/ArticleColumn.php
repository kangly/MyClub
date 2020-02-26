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
}