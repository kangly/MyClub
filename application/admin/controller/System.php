<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/17
 * Time: 14:26
 */
namespace app\admin\controller;

use lmxdawn\tree\Tree;
use think\Model;

/**
 * 系统管理
 * Class System
 * @package app\admin\controller
 */
class System extends Admin
{
    /**
     * 用户界面
     * @return mixed
     */
    public function users()
    {
        return $this->fetch();
    }

    /**
     * 用户信息list
     * @return mixed
     */
    public function user_list()
    {
        return $this->fetch();
    }

    /**
     * search用户信息
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function search_user()
    {
        $map = [];
        $type = input('post.search_type');
        $keyword = input('post.search_keyword');
        if(!$type && $keyword){
            $map['username|nickname|email|mobile'] = ['like',"%$keyword%"];
        }else if($type && $keyword){
            $map[$type] = ['like',"%$keyword%"];
        }

        return model('admin/User')
            ->with('roles')
            ->field('id,username,nickname,email,mobile,left(create_time,16) create_time,status')
            ->where($map)
            ->order('id','asc')
            ->select();
    }

    /**
     * 新增/编辑用户
     * @return mixed
     */
    public function add_user()
    {
        $id = input('get.id');
        $user = null;
        $group_ids = '';

        if($id>0)
        {
            $user = model('admin/User')
                ->where('id','=',$id)
                ->find();

            $access = model('admin/AuthGroupAccess')
                ->where('uid','=',$id)
                ->select();

            foreach($access as $v){
                $group_ids .= $v['group_id'].',';
            }

            $group_ids = rtrim($group_ids,',');
        }

        $this->assign('user',$user);
        $this->assign('group_ids',$group_ids);

        //获取所有用户组
        $group = model('admin/AuthGroup')->all();
        $this->assign('group',$group);

        return $this->fetch();
    }

    /**
     * 保存用户
     */
    public function save_user()
    {
        $group_id = input('post.group_id');
        $username = trim(input('post.username'));
        $email = trim(input('post.email'));
        $mobile = trim(input('post.mobile'));

        if($username && $group_id && $email && $mobile)
        {
            $id = input('post.id');

            if($id>0)
            {
                if(in_array($id,$this->adminUser) && $id != $this->userInfo['uid']){
                    $this->error('禁止编辑超级管理员!');
                }
            }

            $status = input('post.status');
            $nickname = trim(input('post.nickname'));
            $password = trim(input('post.password'));
            $confirm_password = trim(input('post.confirm_password'));

            $user = model('admin/User');

            //验证用户名
            if(!$user->validate_name($id,$username)){
                $this->error($user->getError());
            }

            //验证邮箱
            if(!$user->validate_email($id,$email)){
                $this->error($user->getError());
            }

            //验证电话
            if(!$user->validate_mobile($id,$mobile)){
                $this->error($user->getError());
            }

            $data = [
                'username' => $username,
                'nickname' => $nickname?$nickname:$username,
                'email' => $email,
                'mobile' => $mobile,
                'status' => $status
            ];

            if($id>0)
            {
                if($password)
                {
                    //验证密码
                    if(!$user->validate_password($password,$confirm_password)){
                        $this->error($user->getError());
                    }

                    $data['password'] = md5($password);
                }

                $user->where('id','=',$id)->update($data);
            }
            else
            {
                //验证密码
                if(!$user->validate_password($password,$confirm_password)){
                    $this->error($user->getError());
                }

                $data['password'] = md5($password);
                $data['create_time'] = _time();

                $user_data = $user->create($data);
                $id = $user_data->id;
            }

            //修改用户组

            $group = model('admin/AuthGroupAccess');

            $group->where('uid','=',$id)->delete();

            $group_id_data = explode(',',$group_id);

            $list_data = [];

            foreach($group_id_data as $v)
            {
                $list_data[] = [
                    'uid' => $id,
                    'group_id' => $v
                ];
            }

            if($list_data){
                $group->saveAll($list_data);
            }

            echo $id;
        }
        else
        {
            $this->error('数据非法!');
        }
    }

    /**
     * 用户信息点击编辑
     * 目前单独编辑先禁止修改管理员用户
     */
    public function save_editable_user(){

        $id = input('post.pk');
        $name = input('post.name');
        $value = trim(input('post.value'));

        if($id>0 && $name)
        {
            if(in_array($id,$this->adminUser))
            {
                $this->error('禁止操作超级管理员!');
            }
            else
            {
                $user = model('admin/User');

                if(!$user->validate_user($id,$name,$value)){
                    $this->error($user->getError());
                }

                //昵称为空则默认为用户名
                if($name == 'nickname' && !$value)
                {
                    $username = $user->where('id','=',$id)->field('username')->find();
                    $value = $username->username;
                }

                $user->where('id','=',$id)->update([$name=>$value]);
            }

            $this->success('修改成功!');
        }
        else
        {
            $this->error('非法数据!');
        }
    }

    /**
     * 删除用户,先不限制权限,后续添加
     */
    public function delete_user()
    {
        $id = input('post.id');

        if($id>0)
        {
            //禁止删除超级管理员组的用户
            if(in_array($id,$this->adminUser)){
                $this->error('禁止删除超级管理员用户');
            }

            //禁止删除自身
            if($id == $this->userInfo['uid']){
                $this->error('禁止删除当前用户');
            }

            model('admin/User')->destroy($id);
            model('admin/AuthGroupAccess')->where('uid','=',$id)->delete();

            $this->success('删除成功!');
        }
    }

    /**
     * 用户组index
     * @return mixed
     */
    public function groups()
    {
        return $this->fetch();
    }

    /**
     * 用户组list
     * @return mixed
     */
    public function group_list()
    {
        return $this->fetch();
    }

    /**
     * search用户组
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function search_group()
    {
        return model('admin/AuthGroup')->select();
    }

    /**
     * 添加/编辑用户组
     * @return mixed
     */
    public function add_group()
    {
        $id = input('get.id');

        $group = null;

        if($id>0)
        {
            $map = [
                'id' => $id
            ];

            $group = model('admin/AuthGroup')
                ->where($map)
                ->find();
        }

        $rule_data = getRules([],'id,title text,pid');

        $my_rules = explode(',',$group['rules']);

        foreach($rule_data as $k=>$v)
        {
            $undetermined_rules = [];

            if($group['undetermined_rules']){
                $undetermined_rules = explode(',',$group['undetermined_rules']);
            }

            if(in_array($v['id'], $my_rules) && !in_array($v['id'],$undetermined_rules)){
                $rule_data[$k]['state']['selected'] = true;
            }else{
                $rule_data[$k]['state']['selected'] = false;
            }

            $rule_data[$k]['parent'] = $v['pid'] ? $v['pid'] : '#';
        }

        $tree_json_list = json_encode($rule_data,JSON_UNESCAPED_UNICODE);
        $this->assign('tree_list',$tree_json_list);
        $this->assign('group',$group);

        return $this->fetch();
    }

    /**
     * 保存用户组
     */
    public function save_group()
    {
        $title = input('post.title');
        $status = input('post.status');
        $rule_ids = input('post.rule_ids');
        $undetermined_rules = input('post.undetermined_rules');

        $data = [
            'title' => $title,
            'status' => $status,
            'rules' => $rule_ids,
            'undetermined_rules' => $undetermined_rules
        ];

        $id = input('post.id');

        $group = model('admin/AuthGroup');

        if($id>0)
        {
            $group->where('id','=',$id)->update($data);
        }
        else
        {
            $group = $group->create($data);
            $id = $group->id;
        }

        echo $id;
    }

    /**
     * 删除用户组,先不限制权限,后续会添加
     */
    public function delete_group()
    {
        $id = input('post.id');

        if($id>0)
        {
            model('admin/AuthGroup')->destroy($id);

            echo $id;
        }
    }


    //规则操作

    /**
     * 规则页面
     * @return mixed
     */
    public function rules()
    {
        return $this->fetch();
    }

    /**
     * 规则列表
     * @return mixed
     */
    public function rules_list()
    {
        return $this->fetch();
    }

    /**
     * @return array
     */
    public function search_rules()
    {
        $rule_list = getRules();
        $tree_list = Tree::build($rule_list)->getRootFormat();

        return $tree_list;
    }

    /**
     * 添加/编辑规则
     * @return mixed
     */
    public function add_rule()
    {
        $id = input('get.id');

        $rule = null;

        if($id>0)
        {
            $map = [
                'id' => $id
            ];

            $rule = model('admin/AuthRule')->where($map)
                ->find();
        }

        $this->assign('rule',$rule);

        $rule_list = getRules(['is_menu'=>1]);
        $tree_list = Tree::build($rule_list)->getRootFormat();
        $this->assign('tree_list',$tree_list);

        return $this->fetch();
    }

    /**
     * 保存规则
     */
    public function save_rule()
    {
        $title = input('post.title');
        $name = input('post.name');

        if($title && $name)
        {
            $pid = input('post.pid');
            $status = input('post.status');
            $icon = input('post.icon');
            $is_menu = input('post.is_menu');

            $data = [
                'pid' => $pid,
                'name' => $name,
                'title' => $title,
                'status' => $status,
                'icon' => $icon,
                'is_menu' => $is_menu
            ];

            $id = input('post.id');

            $rule = model('admin/AuthRule');

            if($id>0)
            {
                $rule->where('id','=',$id)->update($data);
            }
            else
            {
                $rule_data = $rule->create($data);
                $id = $rule_data->id;
            }

            echo $id;
        }
    }

    /**
     * 删除规则,先不限制权限,后期添加
     */
    public function delete_rule()
    {
        $id = input('post.id');

        if($id>0)
        {
            model('admin/AuthRule')->destroy($id);

            echo $id;
        }
    }
}