<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/17
 * Time: 09:15
 */
namespace app\admin\model;

use think\Model;

/**
 * User model
 * Class User
 * @package app\admin\model
 */
class User extends Model
{
    protected $error = '';//错误信息

    /**
     * 用户和角色,多对多关联,获取用户所属权限组
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('AuthGroup','auth_group_access','group_id','uid');
    }

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @param null $map
     * @return array|bool|false|\PDOStatement|string|Model
     */
    public function login($username, $password, $map = null)
    {
        $map[] = ['status','=',1];

        $username = trim($username);
        if (checkEmail($username)) {
            $map[] = ['email','=',$username]; //邮箱登陆
        } elseif (checkMobile($username)) {
            $map[] = ['mobile','=',$username]; //手机号登陆
        } else {
            $map[] = ['username','=',$username]; //用户名登陆
        }

        $user = $this->where($map)->find();

        if (!$user) {
            $this->error = '用户不存在或被禁用';
        } else {
            if (md5($password) !== $user['password']) {
                $this->error = '密码错误';
            } else {
                return $user;
            }
        }

        return false;
    }

    /**
     * 检测是否登录
     * @return int
     */
    public function is_login()
    {
        $user = session('user_auth');
        if(empty($user)) {
            return [];
        } else {
            return $user;
        }
    }

    /**
     * 设置登录session
     * @param $user
     * @return mixed
     */
    public function auto_login($user)
    {
        // 记录登录session
        $auth = array(
            'uid'      => $user['id'],
            'username' => $user['username'],
            'nickname' => $user['nickname']
        );

        session('user_auth', $auth);

        return $this->is_login();
    }

    /**
     * 验证用户信息
     * @param $uid
     * @param $name
     * @param $value
     * @return bool
     */
    public function validate_user($uid,$name,$value)
    {
        if($name == 'username')
        {
            $this->validate_name($uid,$value);
        }
        else if($name == 'email')
        {
            $this->validate_email($uid,$value);
        }
        else if($name == 'mobile')
        {
            $this->validate_mobile($uid,$value);
        }

        return true;
    }

    /**
     * 验证用户名
     * @param $id
     * @param $value
     * @return bool
     */
    public function validate_name($id,$value)
    {
        if(!$value)
        {
            $this->error = '用户名必填';
            return false;
        }

        $user = $this
            ->field('id')
            ->where([
                ['id','<>',$id],
                ['username','=',$value]
            ])
            ->find();

        if($user)
        {
            $this->error = '用户名与其他用户重复';
            return false;
        }

        return true;
    }

    /**
     * 验证用户邮箱
     * @param $id
     * @param $value
     * @return bool
     */
    public function validate_email($id,$value)
    {
        if(!checkEmail($value))
        {
            $this->error = '邮箱格式错误';
            return false;
        }

        $user = $this
            ->field('id')
            ->where([
                ['id','<>',$id],
                ['email','=',$value]
            ])
            ->find();

        if($user)
        {
            $this->error = '邮箱与其他用户重复';
            return false;
        }

        return true;
    }

    /**
     * 验证用户电话
     * @param $id
     * @param $value
     * @return bool
     */
    public function validate_mobile($id,$value)
    {
        if(!checkMobile($value))
        {
            $this->error = '电话格式错误';
            return false;
        }

        $user = $this
            ->field('id')
            ->where([
                ['id','<>',$id],
                ['mobile','=',$value]
            ])
            ->find();

        if($user)
        {
            $this->error = '电话与其他用户重复';
            return false;
        }

        return true;
    }

    /**
     * 验证用户密码
     * @param $password
     * @param $confirm_password
     * @return bool
     */
    public function validate_password($password,$confirm_password)
    {
        if(!$password)
        {
            $this->error = '密码必填';
            return false;
        }
        else if(!$confirm_password)
        {
            $this->error = '确认密码必填';
            return false;
        }
        else if($password != $confirm_password)
        {
            $this->error = '两次输入密码不一致';
            return false;
        }

        return true;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}