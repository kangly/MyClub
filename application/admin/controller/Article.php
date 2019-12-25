<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2017/12/27
 * Time: 14:07
 */
namespace app\admin\controller;

use lmxdawn\tree\Tree;
use think\Model;

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
        return model('admin/Article')
            ->order(['sort'=>'desc','id'=>'desc'])
            ->select();
    }

    /**
     * 新增/编辑文章
     * @return mixed
     */
    public function add_article()
    {
        $id = input('get.id');

        $article = null;

        if($id>0)
        {
            $article = model('admin/Article')
                ->where('id','=',$id)
                ->find();
        }

        $this->assign('article',$article);

        $column_list = getColumns([],'id,title,pid');
        $column_list = Tree::build($column_list)->getRootFormat();
        $this->assign('column_list',$column_list);

        return $this->fetch();
    }

    /**
     * 保存文章
     */
    public function save_article()
    {
        $column_id = input('post.column_id');
        $title = input('post.title');
        $content = input('post.content');

        if($column_id>0 && $title && $content)
        {
            $source = input('post.source');
            $source_link = input('post.source_link');

            $data = [
                'title' => $title,
                'column_id' => $column_id,
                'content' => $content,
                'source' => $source,
                'source_link' => $source_link
            ];

            $id = input('post.id');

            $article = model('admin/Article');

            if($id>0)
            {
                $article->where('id','=',$id)->update($data);
            }
            else
            {
                $data['user_id'] = $this->userInfo['uid'];
                $data['user_name'] = $this->userInfo['username'];
                $data['create_time'] = _time();

                $article_data = $article->create($data);
                $id = $article_data->id;
            }

            echo $id;
        }
    }

    /**
     * 删除文章,先不限制权限,后期添加
     */
    public function delete_article()
    {
        $id = input('post.id');

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
        $rule_list = getColumns([],'id,title,pid');
        $tree_list = Tree::build($rule_list)->getRootFormat();

        return $tree_list;
    }

    /**
     * 添加/编辑文章栏目
     * @return mixed
     */
    public function add_column()
    {
        $id = input('get.id');

        $column = null;

        if($id>0)
        {
            $map = [
                'id' => $id
            ];

            $column = model('admin/ArticleColumn')->where($map)
                ->find();
        }

        $this->assign('column',$column);

        $column_list = getColumns([],'id,title,pid');
        $column_list = Tree::build($column_list)->getRootFormat();
        $this->assign('column_list',$column_list);

        return $this->fetch();
    }

    /**
     * 保存文章栏目
     */
    public function save_column()
    {
        $title = input('post.title');

        if($title)
        {
            $pid = input('post.pid');

            $data = [
                'pid' => $pid,
                'title' => $title
            ];

            $id = input('post.id');

            $column = model('admin/ArticleColumn');

            if($id>0)
            {
                $column->where('id','=',$id)->update($data);
            }
            else
            {
                $data['user_id'] = $this->userInfo['uid'];
                $data['user_name'] = $this->userInfo['username'];
                $data['create_time'] = _time();

                $rule = $column->create($data);
                $id = $rule->id;
            }

            echo $id;
        }
    }

    /**
     * 删除规则,先不限制权限,后期添加
     */
    public function delete_column()
    {
        $id = input('post.id');

        if($id>0)
        {
            Model('admin/ArticleColumn')->destroy($id);

            echo $id;
        }
    }
}