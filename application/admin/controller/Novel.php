<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/11
 * Time: 10:02
 */
namespace app\admin\controller;

use think\Model;

class Novel extends Admin
{
    /**
     * 小说管理首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 小说列表
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function novels_list()
    {
        return model('admin/Novel')
            ->order(['id'=>'desc'])
            ->select();
    }

    /**
     * 新增/编辑小说
     * @return mixed
     */
    public function add_novel()
    {
        $id = input('get.id');

        $novel = null;

        if($id>0)
        {
            $novel = model('admin/Novel')
                ->where('id','=',$id)
                ->find();
        }

        $this->assign('novel',$novel);

        return $this->fetch();
    }

    /**
     * 保存文章
     */
    public function save_novel()
    {
        $tid = input('post.tid');
        $title = input('post.title');
        $link = input('post.link');

        if($tid && $title && $link)
        {
            $is_pub = input('post.is_pub');

            $data = [
                'tid' => $tid,
                'title' => $title,
                'link' => $link,
                'is_pub' => $is_pub,
                'nid' => pathinfo($link,PATHINFO_BASENAME)
            ];

            $id = input('post.id');

            $novel = model('admin/Novel');

            if($id>0)
            {
                $novel->where('id','=',$id)->update($data);
            }
            else
            {
                $data['admin_id'] = $this->userInfo['uid'];
                $data['admin_name'] = $this->userInfo['username'];
                $data['create_time'] = _time();

                $novel_data = $novel->create($data);
                $id = $novel_data->id;
            }

            echo $id;
        }
    }

    /**
     * 删除小说,先不限制权限,后期添加
     */
    public function delete_novel()
    {
        $id = input('post.id');

        if($id>0)
        {
            Model('admin/Novel')->destroy($id);

            echo $id;
        }
    }
}