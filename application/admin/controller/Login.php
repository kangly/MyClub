<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/16
 * Time: 15:08
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Config;
use think\facade\Session;
use think\Request;

/**
 * 后台登录控制器
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{
    //登录界面
    public function index()
    {
        if(is_login()){
            $this->redirect(url('/admin/index'));
        }

        return $this->fetch();
    }

    /**
     * 验证登录
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function login(Request $request)
    {
        $url = url('/admin/index');
        if(is_login()){
            $this->error('请勿重复登录',$url);
        }

        if(request()->isPost())
        {
            $username = $request->param('username');
            $password = $request->param('password');

            $user = model('admin/User');
            $user_data = $user->login($username,$password);
            if (!$user_data) {
                $this->error($user->getError());
            }

            $user->where('id','=',$user_data['id'])->update(
                [
                    'last_login_ip' => request()->ip(),
                    'last_login_time' => _time()
                ]
            );

            $user->auto_login($user_data);

            $this->success('登录成功',$url);
        }
        else
        {
            $this->error('非法操作');
        }
    }

    //退出系统,清空session
    public function logout()
    {
        if(is_login()){
            $prefix = Config::get('session.prefix');
            Session::clear($prefix);
        }

        $this->redirect(url('/admin/login'));
    }
}