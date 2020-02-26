<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/10
 * Time: 14:05
 */
namespace app\index\controller;

use QL\QueryList;
use think\paginator\driver\Bootstrap;
use think\Request;

/**
 * 小说模块控制器
 * Class Novel
 * @package app\index\controller
 */
class Novel extends Home
{
    //暂时解决file_get_contents()报错，需要改成curl或更新证书
    //SSL operation failed with code 1. OpenSSL Error messages
    const stream_opts = [
            'ssl' => [
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            ]
        ];

    /**
     * 小说首页
     */
    public function index()
    {
        //采集小说源地址
        $url = 'https://www.biduo.cc';

        //获取源码
        $html = file_get_contents($url,false,stream_context_create(self::stream_opts));

        //采集规则
        $rules = [
            'html' => ['#hotcontent .l .item','html']
        ];

        //分析源码,获取数据
        $data = QueryList::html($html)
            ->rules($rules)
            ->encoding('UTF-8')
            ->removeHead()
            ->query()
            ->getData();

        $one_data = $data->all();
        foreach($one_data as $k=>$v){
            $one_data[$k]['html'] = str_replace('/biquge/','/book/',$v['html']);
        }

        $this->assign('one_data',$one_data);


        //采集规则
        $rules = [
            'html' => ['#hotcontent .r ul','html']
        ];

        //分析源码,获取数据
        $data = QueryList::html($html)
            ->rules($rules)
            ->encoding('UTF-8')
            ->removeHead()
            ->query()
            ->getData();

        $two_data = $data->all();
        if($two_data){
            $two_data['html'] = str_replace('/biquge/','/book/',$two_data[0]['html']);
        }

        $this->assign('two_data',$two_data);


        //采集规则
        $rules = [
            'title' => ['.novelslist .content h2','text'],
            'top' => ['.novelslist .content .top','html'],
            'ul' => ['.novelslist .content ul','html']
        ];

        //分析源码,获取数据
        $data = QueryList::html($html)
            ->rules($rules)
            ->encoding('UTF-8')
            ->removeHead()
            ->query()
            ->getData();

        $thr_data = $data->all();
        foreach($thr_data as $k=>$v){
            $thr_data[$k]['top'] = str_replace('/biquge/','/book/',$v['top']);
            $thr_data[$k]['ul'] = str_replace('/biquge/','/book/',$v['ul']);
        }

        $this->assign('thr_data',$thr_data);


        //采集规则
        $rules = [
            'html' => ['#newscontent .l ul','html']
        ];

        //分析源码,获取数据
        $data = QueryList::html($html)
            ->rules($rules)
            ->encoding('UTF-8')
            ->removeHead()
            ->query()
            ->getData();

        $four_data = $data->all();
        if($four_data){
            $four_data['html'] = str_replace('/biquge/','/book/',$four_data[0]['html']);
        }

        $this->assign('four_data',$four_data);


        //采集规则
        $rules = [
            'html' => ['#newscontent .r ul','html']
        ];

        //分析源码,获取数据
        $data = QueryList::html($html)
            ->rules($rules)
            ->encoding('UTF-8')
            ->removeHead()
            ->query()
            ->getData();

        $five_data = $data->all();
        if($five_data){
            $five_data['html'] = str_replace('/biquge/','/book/',$five_data[0]['html']);
        }

        $this->assign('five_data',$five_data);

        return $this->fetch();
    }

    /**
     * 小说搜索
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $list_data = null;
        $pages_data = null;

        $keyword = $request->param('k');
        $page = $request->param('page');

        if($keyword)
        {
            $encode_keyword = urlencode($keyword);

            $url = 'https://www.biduo.cc/search.php?q='.$encode_keyword;

            if($page && $page>1){
                $url .= '&p='.$page;
            }

            $html = file_get_contents($url,false,stream_context_create(self::stream_opts));

            //采集规则
            $rules = [
                'img' => ['.result-item.result-game-item .result-game-item-pic a img','src'],
                'link' => ['.result-item.result-game-item .result-game-item-pic a','href'],
                'title' => ['.result-item.result-game-item .result-item-title.result-game-item-title a span','text'],
                'intro' => ['.result-item.result-game-item .result-game-item-desc','text'],
                'tags' => ['.result-item.result-game-item .result-game-item-info','html','-p:eq(3)']
            ];

            //分析源码,获取数据
            $data = QueryList::html($html)
                ->rules($rules)
                ->encoding('UTF-8')
                ->removeHead()
                ->query()
                ->getData();

            $list_data = $data->all();
            foreach($list_data as $k=>$v){
                $list_data[$k]['link'] = str_replace('/biquge','/book',$v['link']);
            }

            //获取分页信息

            $page_rules = [
                'pages' => ['.search-result-page-main a:last','href','',function($content) use($encode_keyword){
                    $content = str_replace('/search.php?q='.$encode_keyword.'&p=','',$content);
                    return $content;
                }]
            ];

            //分析源码,获取数据
            $page_data = QueryList::html($html)
                ->rules($page_rules)
                ->encoding('UTF-8')
                ->removeHead()
                ->query()
                ->getData();

            $page_data = $page_data->all();

            //数组分页
            if($page_data && $list_data){
                $pages = $page_data[0]['pages'];

                $current_page = $page ? $page : 1;
                $listRow = 10;

                $p = Bootstrap::make($list_data, $listRow, $current_page, $pages*10, false, [
                    'var_page' => 'page',
                    'path'     => url('/novel/search'),
                    'query'    => [],
                    'fragment' => '',
                ]);

                $p->appends($_GET);
                $pages_data = $p->render();
            }
        }

        $this->assign('list_data',$list_data);
        $this->assign('pages',$pages_data);
        $this->assign('keyword',$keyword);

        return $this->fetch();
    }

    /**
     * 采集具体小说
     * @param Request $request
     * @return mixed
     */
    public function searchnovel(Request $request)
    {
        $novel_data = null;
        $title = null;

        $id = $request->param('id');

        if($id)
        {
            $url = 'https://www.biduo.cc/biquge/'.$id.'/';

            //获取源码
            $html = file_get_contents($url,false,stream_context_create(self::stream_opts));

            //采集规则
            $rules = [
                'title' => ['#list dl dd a','text'],
                'link' => ['#list dl dd a','href']
            ];

            //分析源码,获取数据
            $data = QueryList::html($html)
                ->rules($rules)
                ->encoding('UTF-8')
                ->removeHead()
                ->query()
                ->getData();

            $novel_data = $data->all();
            $novel_data = array_reverse($novel_data);
            foreach($novel_data as $k=>$v){
                $novel_data[$k]['id'] = str_replace(['/biquge/'.$id.'/','.html'],'',$v['link']);
            }

            // 采集小说标题

            $title_rules = [
                'title' => ['#info h1','text']
            ];

            //分析源码,获取数据
            $title_data = QueryList::html($html)
                ->rules($title_rules)
                ->encoding('UTF-8')
                ->removeHead()
                ->query()
                ->getData();

            $title_data = $title_data->all();
            $title = $title_data[0]['title'];
        }

        $this->assign('novel_data',$novel_data);
        $this->assign('title',$title);
        $this->assign('nid',$id);

        return $this->fetch();
    }

    /**
     * 小说详情
     * @param Request $request
     * @return mixed
     */
    public function view(Request $request)
    {
        $id = $request->param('id');
        $nid = $request->param('nid');

        if(!$id && $nid){
            $this->redirect('book/'.$nid);
        }

        if($id && $nid)
        {
            $url = 'https://www.biduo.cc/biquge/'.$nid.'/'.$id.'.html';

            //采集规则
            $rules = [
                'title' => ['.bookname h1','text'],
                'content' => ['#content','html'],
                'last' => ['.bottem2 a:eq(0)','href'],
                'next' => ['.bottem2 a:eq(2)','href'],
            ];

            //分析源码,获取数据
            $data = QueryList::get($url)
                ->rules($rules)
                ->encoding('UTF-8')
                ->removeHead()
                ->query()
                ->getData();

            $view_data = $data->all();

            $view_data[0]['lid'] = str_replace(['/biquge/'.$nid.'/','.html'],'',$view_data[0]['last']);
            $view_data[0]['nid'] = str_replace(['/biquge/'.$nid.'/','.html'],'',$view_data[0]['next']);

            $this->assign('view',$view_data[0]);
            $this->assign('nid',$nid);
            $this->assign('id',$id);
        }

        return $this->fetch();
    }
}