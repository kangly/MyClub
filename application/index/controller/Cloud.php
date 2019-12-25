<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/16
 * Time: 10:40
 */
namespace app\index\controller;

use QL\QueryList;
use think\paginator\driver\Bootstrap;

/**
 * 云资源模块控制器
 * Class Cloud
 * @package app\index\controller
 */
class Cloud extends Home
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
}