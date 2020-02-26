<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;
use think\Request;

class Article extends Controller
{
    /**
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
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

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function view(Request $request)
    {
        $article = [];
        $id = $request->param('id');
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