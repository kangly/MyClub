<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/12/27
 * Time: 14:07
 */
namespace app\admin\controller;

use lmxdawn\tree\Tree;
use think\Request;

class Article extends Admin
{
    /**
     * 文章管理首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 文章管理列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function article_list()
    {
        return $this->fetch();
    }

    public function search_article()
    {
        $data = model('admin/Article')
            ->field('id,title,is_publish,column_id')
            ->order(['sort'=>'desc','id'=>'desc'])
            ->select();

        foreach ($data as $k=>$v){
            $v->column;
        }

        return $data;
    }

    /**
     * 新增/编辑文章
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_article(Request $request)
    {
        $id = $request->param('id');

        $article = null;

        if($id>0)
        {
            $article = model('admin/Article')->where('id','=',$id)->find();
        }

        $this->assign('article',$article);

        $column_list = getColumns([],'id,title,pid');
        $column_list = Tree::build($column_list)->getRootFormat();
        $this->assign('column_list',$column_list);

        return $this->fetch();
    }

    /**
     * 保存文章
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save_article(Request $request)
    {
        $column_id = $request->param('column_id');
        $title = $request->param('title');
        $content = $request->param('content');

        if($column_id>0 && $title && $content)
        {
            $thumb = '';
            $image = controller('Image','service');
            $img = $image->uploadFile('thumb');
            if($img['status']=='success'){
                $thumb = $img['info'];
            }else if($img['status']=='error'){
                echo $img['info'];
                exit;
            }

            $data = [
                'column_id' => $column_id,
                'title' => $title,
                'summary' => $request->param('summary'),
                'content' => $content,
                'source' => $request->param('source'),
                'source_link' => $request->param('source_link')
            ];

            $article = model('admin/Article');

            $id = $request->param('id');
            if($id>0)
            {
                if($thumb){
                    $data['thumb'] = $thumb;
                }
                $article->where('id','=',$id)->update($data);
            }
            else
            {
                $data['thumb'] = $thumb;
                $data['user_id'] = $this->userInfo['uid'];
                $data['username'] = $this->userInfo['username'];
                $data['create_time'] = _time();

                $article_data = $article->create($data);
                $id = $article_data->id;
            }

            echo $id;
        }
    }

    /**
     * wangEditor编辑器上传图片
     * @return \think\response\Json
     */
    public function uploadImages()
    {
        $image = controller('Image','service');
        return $image->uploadImages();
    }

    /**
     * 删除文章,先不限制权限,后期添加
     * @param Request $request
     */
    public function delete_article(Request $request)
    {
        $id = $request->param('id');

        if($id>0)
        {
            Model('admin/Article')->destroy($id);

            echo $id;
        }
    }

    /**
     * 文章栏目首页
     * @return mixed
     */
    public function column()
    {
        return $this->fetch();
    }

    /**
     * 文章栏目列表
     */
    public function column_list()
    {
        return $this->fetch();
    }

    public function search_column()
    {
        $rule_list = getColumns([],'id,title,pid,intro');
        $tree_list = Tree::build($rule_list)->getRootFormat();

        return $tree_list;
    }

    /**
     * 添加/编辑文章栏目
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add_column(Request $request)
    {
        $column = null;

        $id = $request->param('id');
        if($id>0){
            $column = model('admin/ArticleColumn')->where(['id'=>$id])->find();
        }

        $this->assign('column',$column);

        $column_list = getColumns([],'id,title,pid');
        $column_list = Tree::build($column_list)->getRootFormat();
        $this->assign('column_list',$column_list);

        return $this->fetch();
    }

    /**
     * 保存文章栏目
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save_column(Request $request)
    {
        $title = $request->param('title');

        if($title)
        {
            $data = [
                'pid' => $request->param('pid'),
                'title' => $title,
                'intro' => $request->param('intro')
            ];

            $column = model('admin/ArticleColumn');

            $id = $request->param('id');
            if($id>0)
            {
                $column->where('id','=',$id)->update($data);
            }
            else
            {
                $data['user_id'] = $this->userInfo['uid'];
                $data['username'] = $this->userInfo['username'];
                $data['create_time'] = _time();

                $rule = $column->create($data);
                $id = $rule->id;
            }

            echo $id;
        }
    }

    /**
     * 删除规则,先不限制权限,后期添加
     * @param Request $request
     */
    public function delete_column(Request $request)
    {
        $id = $request->param('id');

        if($id>0)
        {
            Model('admin/ArticleColumn')->destroy($id);

            echo $id;
        }
    }
}