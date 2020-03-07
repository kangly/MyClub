<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;
use think\Request;

class Category extends Controller
{
    protected $middleware = ['Check'];

    /**
     * @return \think\response\Json
     */
    public function index()
    {
        $items_json = session('column');
        if($items_json){
            $items = json_decode($items_json,true);
        }else{
            $items = model('admin/ArticleColumn')->getColumns([],'id,title,intro');
            session('column',json_encode($items));
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
        $id = $request->param('id');
        $page = $request->param('page');
        /*$items_json = session('article_column_'.$page.'_'.$id);
        if($items_json){
            $items = json_decode($items_json,true);
        }else{
            $items = model('admin/ArticleColumn')->articles_list($id,$page);
            foreach ($items as $k=>$v){
                $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
            }
            session('article_column_'.$page.'_'.$id,json_encode($items));
        }*/
        $items = model('admin/ArticleColumn')->articles_list($id,$page);
        foreach ($items as $k=>$v){
            $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
        }
        $data = [
            'message' => 'success',
            'articles' => $items
        ];

        return json($data);
    }
}