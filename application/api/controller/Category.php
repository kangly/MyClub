<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;
use think\Request;

class Category extends Controller
{
    /**
     * @return \think\response\Json
     */
    public function index()
    {
        $items_json = session('column_info');
        if($items_json){
            $items = json_decode($items_json,true);
        }else{
            $items = model('admin/ArticleColumn')->getColumns([],'id,title,intro');
            session('column_info',json_encode($items));
        }

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
        $items = model('admin/ArticleColumn')->articles_list($request->param('id'),$request->param('page'));
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