<?php
namespace app\api\controller;

use think\Controller;
use think\facade\Config;
use think\Request;

class Article extends Controller
{
    protected $middleware = ['Check'];

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function show(Request $request)
    {
        $page = $request->param('page');
        $items = model('admin/Article')->articles_list([],$page);
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
     * @return \think\response\Json
     */
    public function recommend()
    {
        $items_json = session('recommend');
        if($items_json){
            $items = json_decode($items_json,true);
        }else{
            $map = [];
            $map[] = ['is_recommend','=',1];
            $items = model('admin/Article')->articles_list($map,1,3);
            foreach ($items as $k=>$v){
                $items[$k]['thumb'] = Config::get('website_url').'/'.$v['thumb'];
            }
            session('recommend',json_encode($items));
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
    public function info(Request $request)
    {
        $article = [];
        $id = $request->param('id');
        if($id>0){
            $article = model('admin/Article')->article_view($id);
            $uid = $request->param('uid');
            if($uid>0){
                $store = model('admin/ArticleStore')
                    ->field('id')
                    ->where([
                        ['article_id','=',$id],
                        ['member_id','=',$uid]
                    ])
                    ->find();
                if($store['id']>0){
                    $article['store'] = 1;
                }else{
                    $article['store'] = 0;
                }
            }
        }

        $data = [
            'msg' => 'success',
            'article' => $article
        ];

        return json($data);
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
        }

        $data = [
            'message' => 'success',
            'articles' => $items
        ];

        return json($data);
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function store(Request $request)
    {
        $id = $request->param('id');
        $uid = $request->param('uid');
        if($id>0 && $uid>0){
            $ArticleStore = model('admin/ArticleStore');
            $store = $ArticleStore
                ->field('id')
                ->where([
                    ['article_id','=',$id],
                    ['member_id','=',$uid]
                ])
                ->find();
            if ($store['id']>0){
                $ArticleStore->where([
                    ['article_id','=',$id],
                    ['member_id','=',$uid]
                ])->delete();
                return json_encode(['msg'=>'success']);
            }else{
                $ArticleStore->insert([
                    'article_id'=>$id,
                    'member_id'=>$uid,
                    'create_time'=>_time()
                ]);
                return json_encode(['msg'=>'success']);
            }
        }else{
            return json_encode(['msg'=>'error']);
        }
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function storeList(Request $request)
    {
        $article_list = [];
        $uid = $request->param('uid');
        if($uid>0){
            $article_list = model('admin/Member')->get($uid)->articles->hidden(['pivot']);
        }
        $data = [
            'msg' => 'success',
            'articles' => $article_list
        ];

        return json($data);
    }
}