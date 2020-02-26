<?php
namespace app\api\controller;

use think\Controller;

class Category extends Controller
{
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
}