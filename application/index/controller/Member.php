<?php
namespace app\index\controller;

/**
 * 用户中心
 * Class Member
 * @package app\index\controller
 */
class Member extends Home
{
    public function index()
    {
        return $this->fetch();
    }
}