<?php
namespace app\admin\controller;

class Index extends Admin
{
    /**
     * 控制台首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
}