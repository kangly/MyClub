<?php
namespace app\index\controller;

use think\Db;
use think\facade\Session;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use think\Request;

class Index extends Home
{
    /**
     * 前台首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 注册
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register(Request $request)
    {
        $result = [];

        if(request()->isPost())
        {
            $email = trim($request->param('email'));
            $nickname = trim($request->param('nickname'));
            $password = trim($request->param('password'));

            if(!$email)
            {
                $result['status'] = 'error';
                $result['code'] = 401;
                $result['type'] = 'email';
                $result['text'] = '邮箱不能为空!';

                return json($result);
            }

            if(!checkEmail($email))
            {
                $result['status'] = 'error';
                $result['code'] = 402;
                $result['type'] = 'email';
                $result['text'] = '邮箱格式错误!';

                return json($result);
            }

            $is_exist = Db::name('user')->field('id')->where('email',$email)->find();
            if($is_exist && $is_exist['id'])
            {
                $result['status'] = 'error';
                $result['code'] = 403;
                $result['type'] = 'email';
                $result['text'] = '邮箱已经注册!';

                return json($result);
            }

            if(!$nickname)
            {
                $result['status'] = 'error';
                $result['code'] = 404;
                $result['type'] = 'nickname';
                $result['text'] = '昵称不能为空!';

                return json($result);
            }

            $is_exist = Db::name('user')->field('id')->where('nickname',$nickname)->find();
            if($is_exist && $is_exist['id'])
            {
                $result['status'] = 'error';
                $result['code'] = 405;
                $result['type'] = 'nickname';
                $result['text'] = '昵称已存在!';

                return json($result);
            }

            if(!$password)
            {
                $result['status'] = 'error';
                $result['code'] = 406;
                $result['type'] = 'password';
                $result['text'] = '密码不能为空!';

                return json($result);
            }

            $pwt = strlen($password);

            if($pwt>=6 && $pwt<=16 && preg_match('/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/',$password))
            {
                $data = [
                    'email' => $email,
                    'nickname' => $nickname,
                    'password' => md5($password),
                    'create_time' => _time()
                ];

                Db::name('user')->insert($data);

                $result['status'] = 'success';
                $result['code'] = 200;
                $result['text'] = '注册成功!';

                //$this->SendMail($email);

                return json($result);
            }
            else
            {
                $result['status'] = 'error';
                $result['code'] = 407;
                $result['type'] = 'password';
                $result['text'] = '密码6~16位,且为数字和字母组合!';

                return json($result);
            }
        }
        else
        {
            $result['status'] = 'error';
            $result['text'] = '非法操作!';

            return json($result);
        }
    }

    /**
     * 登录
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function login(Request $request)
    {
        $result = [];

        if(request()->isPost())
        {
            $email = $request->param('email');
            $password = $request->param('password');

            if(!$email)
            {
                $result['status'] = 'error';
                $result['code'] = 401;
                $result['type'] = 'email';
                $result['text'] = '邮箱不能为空!';

                return json($result);
            }

            if(!checkEmail($email))
            {
                $result['status'] = 'error';
                $result['code'] = 402;
                $result['type'] = 'email';
                $result['text'] = '邮箱格式错误!';

                return json($result);
            }

            if(!$password)
            {
                $result['status'] = 'error';
                $result['code'] = 403;
                $result['type'] = 'password';
                $result['text'] = '密码不能为空!';

                return json($result);
            }

            $is_exist = Db::name('user')->where('email',$email)->find();
            if($is_exist && $is_exist['id'])
            {
                if(md5($password)==$is_exist['password'])
                {
                    $result['status'] = 'success';
                    $result['code'] = 200;
                    $result['text'] = '登录成功!';

                    // 更新登录信息
                    Db::name('user')->where('id',$is_exist['id'])->update([
                        'last_login_ip' => request()->ip(),
                        'last_login_time' => _time()
                    ]);

                    // 记录登录session
                    $auth = array(
                        'uid' => $is_exist['id'],
                        'nickname' => $is_exist['nickname'],
                        'email' => $is_exist['email']
                    );

                    session('user_auth', $auth, 'front');

                    return json($result);
                }
                else
                {
                    $result['status'] = 'error';
                    $result['code'] = 404;
                    $result['type'] = 'password';
                    $result['text'] = '密码错误!';

                    return json($result);
                }
            }
            else
            {
                $result['status'] = 'error';
                $result['code'] = 405;
                $result['type'] = 'email';
                $result['text'] = '用户不存在!';

                return json($result);
            }
        }
        else
        {
            $result['status'] = 'error';
            $result['text'] = '非法操作!';

            return json($result);
        }
    }

    /**
     * 注销
     */
    public function sign_out()
    {
        if(request()->isPost())
        {
            Session::clear('front');

            echo 'success';
        }
        else
        {
            echo '非法操作!';
        }
    }

    //网站声明
    public function instructions()
    {
        return $this->fetch();
    }

    protected function SendMail($email='')
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        $msg = new AMQPMessage($email);
        $channel->basic_publish($msg, '', 'hello');
        //echo " [x] Sent 'Hello World!'\n";
        $channel->close();
        $connection->close();
    }

    public function test()
    {

    }
}