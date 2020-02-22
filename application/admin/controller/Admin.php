<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/12/2
 * Time: 08:55
 */
namespace app\admin\controller;

use think\Controller;
use kly\Auth;

class Admin extends Controller
{
    public $userInfo = [];//当前登录用户信息
    public $adminUser = [];//超级管理员组用户

    //初始化一些数据
    function initialize()
    {
        if(!is_login()){
            $this->redirect(url('/admin/login'));
        }

        $this->userInfo = session('user_auth');

        $admin_user = session('admin_user');
        if(empty($admin_user)){
            $admin_user = $this->load_admin_user();
        }
        $this->adminUser = $admin_user;

        $menu_list = session('menu_list');
        if(empty($menu_list)){
            $this->check_auth();
            $menu_list = $this->get_menu();
            session('menu_list',$menu_list);
        }

        $this->assign('userInfo',$this->userInfo);
        $this->assign('rule_list',$menu_list);
        $this->assign('web_title','有家小栈后台管理系统');
    }

    /**
     * 超级管理员id数组
     * @return array
     */
    protected function load_admin_user()
    {
        $admin_user = model('admin/AuthGroupAccess')
            ->field('uid')
            ->where('group_id','=',1)
            ->select();

        $admin_user_data = [];
        foreach($admin_user as $v){
            $admin_user_data[] = $v['uid'];
        }

        return $admin_user_data;
    }

    //验证访问权限,超级管理员跳过验证
    protected function check_auth()
    {
        if(!in_array($this->userInfo['uid'],$this->adminUser)){
            $auth = new Auth();
            $model = request()->module();
            $controller = request()->controller();
            $action = request()->action();
            $check_name = '/' . $model . '/'. $controller . '/' .$action;
            if(!$auth->check($check_name, $this->userInfo['uid'])){
                $this->error('无操作权限');
            }
        }
    }

    /**
     * 左侧两级导航菜单,超级管理员显示所有菜单
     * @return array
     */
    protected function get_menu()
    {
        $map = [
            ['type','=',1],
            ['status','=',1],
            ['is_menu','=',1]
        ];

        if(!in_array($this->userInfo['uid'],$this->adminUser)){
            $auth = new Auth();
            $groups = $auth->getGroups($this->userInfo['uid']);
            $rules = '';
            foreach($groups as $v){
                $rules .= $v['rules'].',';
            }
            $rules = rtrim($rules,',');
            $rules_data = explode(',',$rules);
            $rules_data = array_unique($rules_data);
            if($rules_data){
                $map[] = ['id','in',$rules_data];
            }
        }

        $menu_list = list2tree(getRules($map));

        return $menu_list;
    }
}