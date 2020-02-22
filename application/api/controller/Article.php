<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;

class Article extends Controller
{
    public function index()
    {
        $items = model('admin/Article')
            ->field('id,title,summary,thumb,left(create_time,16) posted_at')
            ->where('is_publish','=',1)
            ->paginate(4);

        foreach ($items as $k=>$v)
        {
            $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
            $items[$k]['views'] = mt_rand(1,100);
        }

        $data = [
            'message' => 'success',
            'articles' => $items
        ];

        return json($data);
    }

    public function view()
    {
        $article = [];
        $id = input('id');
        if($id>0){
           $article = model('admin/Article')
               ->field('id,title,content,username author,left(create_time,16) posted_at')
               ->where('id','=',$id)
               ->find();

            $article['views'] = mt_rand(1,100);
            $article['votes'] = mt_rand(1,100);
        }

        return json($article);
    }
}