<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/23
 * Time: 13:54
 */
namespace app\admin\model;

use think\Model;

class AuthRule extends Model
{
    /**
     * 获取规则列表
     * @param array $map
     * @param string $fields
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRules($map=[],$fields='*')
    {
        $rules = $this
            ->field($fields)
            ->where($map)
            ->order('id','asc')
            ->select();

        return $rules;
    }
}