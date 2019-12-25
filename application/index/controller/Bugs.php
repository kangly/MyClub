<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/23
 * Time: 15:25
 */
namespace app\index\controller;

/**
 * Bug反馈控制器
 * Class Bugs
 * @package app\index\controller
 */
class Bugs extends Home
{
    /**
     * Bug反馈首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
}