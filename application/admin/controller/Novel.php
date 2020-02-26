<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/11
 * Time: 10:02
 */
namespace app\admin\controller;

use think\Request;

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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function novels_list()
    {
        return model('admin/Novel')
            ->order(['id'=>'desc'])
            ->select();
    }

    /**
     * 新增/编辑小说
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_novel(Request $request)
    {
        $novel = null;

        $id = $request->param('id');
        if($id>0){
            $novel = model('admin/Novel')->where('id','=',$id)->find();
        }

        $this->assign('novel',$novel);

        return $this->fetch();
    }

    /**
     * 保存文章
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save_novel(Request $request)
    {
        $tid = $request->param('tid');
        $title = $request->param('title');
        $link = $request->param('link');

        if($tid && $title && $link)
        {
            $data = [
                'tid' => $tid,
                'title' => $title,
                'link' => $link,
                'is_pub' => $request->param('is_pub'),
                'nid' => pathinfo($link,PATHINFO_BASENAME)
            ];

            $novel = model('admin/Novel');

            $id = $request->param('id');
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
     * @param Request $request
     */
    public function delete_novel(Request $request)
    {
        $id = $request->param('id');

        if($id>0)
        {
            Model('admin/Novel')->destroy($id);

            echo $id;
        }
    }
}