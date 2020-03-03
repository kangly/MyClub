<?php
namespace app\api\controller;

use think\facade\Config;
use think\Request;

class Article extends Base
{
    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function index(Request $request)
    {
        $items = model('admin/Article')->articles_list([],$request->param('page'));
        foreach ($items as $k=>$v){
            $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
            $items[$k]['views'] = mt_rand(1,100);
        }

        $data = [
            'message' => 'success',
            'articles' => $items
        ];

        return json($data);
    }

    /**
     * @return \think\response\Json
     */
    public function recommend()
    {
        $map = [];
        $map[] = ['is_recommend','=',1];
        $items = model('admin/Article')->articles_list($map,1,3);
        foreach ($items as $k=>$v){
            $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
        }

        $data = [
            'message' => 'success',
            'articles' => $items
        ];

        return json($data);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function view(Request $request)
    {
        $article = [];
        $id = $request->param('id');
        if($id>0){
           $article = model('admin/Article')->article_view($id);
            $article['views'] = mt_rand(1,100);
        }

        return json($article);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function search(Request $request)
    {
        $map = [];
        $keywords = $request->param('keywords');
        if($keywords){
            $map[] = ['title','like',sprintf('%%%s%%',$keywords)];
        }
        $items = model('admin/Article')->articles_list($map,$request->param('page'));
        foreach ($items as $k=>$v){
            $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
            $items[$k]['views'] = mt_rand(1,100);
        }

        $data = [
            'message' => 'success',
            'articles' => $items
        ];

        return json($data);
    }
}