<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/10/24
 * Time: 16:28
 */
namespace app\index\controller;

class Error extends Home
{
    public function index()
    {
        return $this->fetch();
    }
}