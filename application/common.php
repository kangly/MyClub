<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 返回当前时间
 * @return bool|string
 */
function _time()
{
    return date('Y-m-d H:i:s');
}

/**
 * 验证邮箱格式
 * @param $email
 * @return bool
 */
function checkEmail($email)
{
    if($email){
        $pattern = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
        if(preg_match($pattern,$email)){
            return true;
        }
    }
    return false;
}

/**
 * 验证手机格式
 * @param $mobile
 * @return bool
 */
function checkMobile($mobile)
{
    if($mobile){
        if(preg_match("/^1\d{10}$/", $mobile)){
            return true;
        }
    }
    return false;
}

/**
 * 生成随机数,可以指定前缀
 * @param string $prefix
 * @return string
 */
function uuid($prefix = '')
{
    $str = md5(uniqid(mt_rand(), true));
    $uuid  = substr($str,0,8);
    $uuid .= substr($str,8,4);
    $uuid .= substr($str,12,4);
    $uuid .= substr($str,16,4);
    $uuid .= substr($str,20,12);
    return $prefix . $uuid;
}

/**
 * 检测是否登录
 * @return mixed
 */
function is_login()
{
    return model('admin/User')->is_login();
}

/**
 * 根据条件返回规则列表
 * @param array $map
 * @param string $fields
 * @return mixed
 */
function getRules($map = [],$fields='*')
{
    return model('admin/AuthRule')->getRules($map,$fields)->toArray();
}

/**
 * 根据条件返回栏目列表
 * @param array $map
 * @param string $fields
 * @return mixed
 */
function getColumns($map = [],$fields='*')
{
    return model('admin/ArticleColumn')->getColumns($map,$fields)->toArray();
}

/**
 * 将数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pk ID标记字段
 * @param string $pid parent标记字段
 * @param string $child 子代key名称
 * @param int $root 返回的根节点ID
 * @param bool $strict 默认非严格模式
 * @return array
 */
function list2tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0, $strict = false)
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parent_id = isset($data[$pid]) ? $data[$pid] : null;
            if ($parent_id === null || (String) $root === $parent_id) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parent_id])) {
                    $parent           = &$refer[$parent_id];
                    $parent[$child][] = &$list[$key];
                } else {
                    if ($strict === false) {
                        $tree[] = &$list[$key];
                    }
                }
            }
        }
    }
    return $tree;
}

/**
 * 二维数组根据字段进行排序
 * @param array $array 要排序的数组
 * @param string $field 要排序的字段
 * @param string $sort SORT_DESC 降序；SORT_ASC 升序
 * @return mixed
 */
function array_sort_by_field($array,$field,$sort='SORT_ASC')
{
    $sort_data = [];
    foreach ($array as $k=>$v){
        foreach ($v as $m=>$n){
            $sort_data[$m][$k] = $n;
        }
    }

    array_multisort($sort_data[$field],constant($sort),$array);
    return $array;
}

/**
 * curl get
 * @param $url
 * @param array $headers
 * @return mixed|string
 */
function get_curl($url,$headers=[])
{
    $timeout = 30;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_TIMEOUT,$timeout);
    if($headers){
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
    }
    if(strpos($url, 'https') === 0){
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    }else{
        curl_setopt($curl, CURLOPT_URL ,$url);
    }
    $result = curl_exec($curl);
    if(curl_errno($curl)){
        return curl_error($curl);
    }
    curl_close($curl);
    return $result;
}

/**
 * curl post
 * @param $url
 * @param array $header
 * @param string $post_string
 * @return mixed
 */
function post_curl($url,$header=[],$post_string=''){
    $timeout = 30;
    if(is_array($post_string)){
        $string = '';
        foreach ($post_string as $k=>$v){
            $string .= sprintf("%s=%s&",$k,$v);
        }
        $post_string = $string;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    if($header){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * 不同类型的开始(含)、结束日期(不含)
 * @param $type
 * @return array
 */
function get_time($type = null)
{
    $start_date = null;
    $end_date = null;

    if($type == 'today')
    {
        //今天
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d',strtotime('+1 day'));
    }
    else if($type == 'yesterday')
    {
        //昨天
        $start_date = date('Y-m-d',strtotime('-1 day'));
        $end_date = date('Y-m-d');
    }
    else if($type == 'tomorrow')
    {
        //明天
        $start_date = date('Y-m-d',strtotime('+1 day'));
        $end_date = date('Y-m-d',strtotime("$start_date +1 day"));
    }
    else if($type == 'this_week')
    {
        //本周
        $start_date = date('Y-m-d',strtotime('this week Monday'));
        $end_date = date('Y-m-d',strtotime('next week Monday'));
    }
    else if($type == 'last_week')
    {
        //上周
        $start_date = date('Y-m-d',strtotime('last week Monday'));
        $end_date = date('Y-m-d',strtotime('this week Monday'));
    }
    else if($type == 'next_week')
    {
        //下周
        $start_date = date('Y-m-d',strtotime('next week Monday'));
        $end_date = date('Y-m-d',strtotime("$start_date +1 week"));
    }
    else if($type == 'this_month')
    {
        //本月
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-01',strtotime('next Month'));
    }
    else if($type == 'last_month')
    {
        //上月
        $start_date = date('Y-m-01',strtotime('last Month'));
        $end_date = date('Y-m-01');
    }
    else if($type == 'next_month')
    {
        //下月
        $start_date = date('Y-m-01',strtotime('next Month'));
        $end_date = date('Y-m-01',strtotime("$start_date +1 month"));
    }
    else if($type == 'this_year')
    {
        //今年
        $start_date = date('Y-01-01');
        $end_date = date('Y-01-01',strtotime("$start_date +1 year"));
    }
    else if($type == 'last_year')
    {
        //去年
        $start_date = date('Y-01-01',strtotime('-1 year'));
        $end_date = date('Y-01-01');
    }
    else if($type == 'next_year')
    {
        //明年
        $start_date = date('Y-01-01',strtotime('+1 year'));
        $end_date = date('Y-01-01',strtotime("$start_date +1 year"));
    }

    $data = [
        'start_date' => $start_date,
        'end_date' => $end_date
    ];

    return $data;
}