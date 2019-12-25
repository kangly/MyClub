<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/3/14
 * Time: 15:20
 */
namespace app\index\controller;

class Preview extends Home
{
    public function index(){

        return $this->fetch();
    }
}