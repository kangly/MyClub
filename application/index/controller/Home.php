<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/11
 * Time: 09:36
 */
namespace app\index\controller;

use think\Controller;
use think\facade\View;

/**
 * 前台公共控制器,初始化,处理一些公共数据
 * Class Home
 * @package app\index\controller
 */
class Home extends Controller
{
    public $userInfo = [];//当前登录用户信息

    function initialize()
    {
        $this->userInfo = session('user_auth','','front');
        $this->assign('userInfo',$this->userInfo);

        // 赋值全局模板变量
        View::share('name','测试');
    }
}