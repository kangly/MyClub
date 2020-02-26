<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/11/17
 * Time: 14:26
 */
namespace app\admin\controller;

use lmxdawn\tree\Tree;
use think\Request;

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
     * @param Request $request
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function search_user(Request $request)
    {
        $map = [];
        $type = $request->param('search_type');
        $keyword = $request->param('search_keyword');
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
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_user(Request $request)
    {
        $user = null;
        $group_ids = '';
        $id = $request->param('id');

        if($id>0)
        {
            $user = model('admin/User')->where('id','=',$id)->find();

            $access = model('admin/AuthGroupAccess')->where('uid','=',$id)->select();
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
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save_user(Request $request)
    {
        $group_id = $request->param('group_id');
        $username = trim($request->param('username'));
        $email = trim($request->param('email'));
        $mobile = trim($request->param('mobile'));

        if($username && $group_id && $email && $mobile)
        {
            $id = $request->param('id');

            if($id>0)
            {
                if(in_array($id,$this->adminUser) && $id != $this->userInfo['uid']){
                    $this->error('禁止编辑超级管理员!');
                }
            }

            $status = $request->param('status');
            $nickname = trim($request->param('nickname'));
            $password = trim($request->param('password'));
            $confirm_password = trim($request->param('confirm_password'));

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
     * 用户信息点击编辑 目前单独编辑先禁止修改管理员用户
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function save_editable_user(Request $request)
    {
        $id = $request->param('pk');
        $name = $request->param('name');
        $value = trim($request->param('value'));

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
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete_user(Request $request)
    {
        $id = $request->param('id');

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
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_group(Request $request)
    {
        $group = null;
        $id = $request->param('id');
        if($id>0){
            $group = model('admin/AuthGroup')->where(['id'=>$id])->find();
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
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save_group(Request $request)
    {
        $title = $request->param('title');
        $status = $request->param('status');
        $rule_ids = $request->param('rule_ids');
        $undetermined_rules = $request->param('undetermined_rules');

        $data = [
            'title' => $title,
            'status' => $status,
            'rules' => $rule_ids,
            'undetermined_rules' => $undetermined_rules
        ];

        $id = $request->param('id');

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
     * @param Request $request
     */
    public function delete_group(Request $request)
    {
        $id = $request->param('id');

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
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_rule(Request $request)
    {
        $rule = null;
        $id = $request->param('id');
        if($id>0){
            $rule = model('admin/AuthRule')->where(['id'=>$id])->find();
        }

        $this->assign('rule',$rule);

        $rule_list = getRules(['is_menu'=>1]);
        $tree_list = Tree::build($rule_list)->getRootFormat();
        $this->assign('tree_list',$tree_list);

        return $this->fetch();
    }

    /**
     * 保存规则
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save_rule(Request $request)
    {
        $title = $request->param('title');
        $name = $request->param('name');

        if($title && $name)
        {
            $data = [
                'pid' => $request->param('pid'),
                'name' => $name,
                'title' => $title,
                'status' => $request->param('status'),
                'icon' => $request->param('icon'),
                'is_menu' => $request->param('is_menu')
            ];

            $rule = model('admin/AuthRule');

            $id = $request->param('id');

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
     * @param Request $request
     */
    public function delete_rule(Request $request)
    {
        $id = $request->param('id');

        if($id>0)
        {
            model('admin/AuthRule')->destroy($id);

            echo $id;
        }
    }
}