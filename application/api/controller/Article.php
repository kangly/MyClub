<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;
use think\Request;

class Article extends Controller
{
    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function index(Request $request)
    {
        $items = model('admin/Article')->articles_list($request->param('page'));
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
}