<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rule('api/category/article', 'api/category/article','GET');
Route::rule('api/category', 'api/category/index','GET');
Route::rule('api/article/search', 'api/article/search','GET');
Route::rule('api/articles/:page', 'api/article/index','GET');
Route::rule('api/article/:id', 'api/article/view','GET');
Route::rule('novel/index', 'index/novel/index','GET')->cache(3600);
Route::rule('novel/view/:nid/:id', 'index/novel/view','GET')->cache(3600);
Route::rule('novel/search', 'index/novel/search','GET')->cache(3600);
Route::rule('book/:id', 'index/novel/searchnovel','GET')->cache(3600);
Route::rule('member/index', 'index/member/index','GET')->cache(false);
Route::rule('cloud/', 'index/cloud/index','GET')->cache(false);
Route::rule('test/', 'index/test/index','GET')->cache(false);
Route::rule('bugs/', 'index/bugs/index','GET')->cache(false);
Route::rule('index/index', 'index/index/index','GET')->cache(false);
Route::rule('error', 'index/error/index','GET')->cache(false);
Route::rule('books/', 'index/books/index','GET')->cache(false);
Route::rule('index/register', 'index/index/register','POST')->cache(false);
Route::rule('index/login', 'index/index/login','POST')->cache(false);
Route::rule('index/sign_out', 'index/index/sign_out','POST')->cache(false);
Route::rule('instructions', 'index/index/instructions','GET')->cache(false);
Route::rule('/', 'index/index/index','GET')->cache(false);