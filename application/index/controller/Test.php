<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/4/7
 * Time: 21:53
 */
namespace app\index\controller;

use app\common\controller\Mailer;

/**
 * Bug反馈控制器
 * Class Bugs
 * @package app\index\controller
 */
class Test extends Home
{
    /**
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    public function SendMailer()
    {
        $mailer = new Mailer();
        $mailer->SendMailer('614797580@qq.com');
    }
}