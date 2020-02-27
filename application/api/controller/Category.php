<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;
use think\Request;

class Category extends Controller
{
    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $items = model('admin/ArticleColumn')
            ->field('id,title,intro')
            ->select();

        $data = [
            'message' => 'success',
            'items' => $items
        ];

        return json($data);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function article(Request $request)
    {
        $items = model('admin/ArticleColumn')
            ->articles_list($request->param('id'),$request->param('page'));

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
}