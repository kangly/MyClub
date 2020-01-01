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

Route::get('novel/index', 'index/novel/index')->cache(3600);
Route::get('novel/view/:nid/:id', 'index/novel/view')->cache(3600);
Route::get('novel/search', 'index/novel/search')->cache(3600);
Route::get('novel/searchnovel/:id', 'index/novel/searchnovel')->cache(3600);
Route::get('book/:id$', 'index/novel/searchnovel')->cache(3600);
Route::get('book/:nid/:id$', 'index/novel/view')->cache(3600);
Route::get('member/index', 'index/member/index')->cache(false);
Route::get('cloud/', 'index/cloud/index')->cache(false);
Route::get('test/', 'index/test/index')->cache(false);
Route::get('bugs/', 'index/bugs/index')->cache(false);
Route::get('index/index', 'index/index/index')->cache(false);
Route::get('error', 'index/error/index')->cache(false);
Route::get('books/', 'index/books/index')->cache(false);
Route::post('index/register', 'index/index/register')->cache(false);
Route::post('index/login', 'index/index/login')->cache(false);
Route::post('index/sign_out', 'index/index/sign_out')->cache(false);
Route::get('instructions', 'index/index/instructions')->cache(false);
Route::get('preview/index', 'index/preview/index')->cache(false);
Route::get('resume/index', 'index/resume/index')->cache(false);
Route::get('resume/download', 'index/resume/download')->cache(false);
Route::get('/', 'index/index/index')->cache(false);