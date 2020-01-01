<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/11/16
 * Time: 15:56
 */
namespace app\index\controller;

class Books extends Home
{
    public function index()
    {
        $books_data = [
            [
                'title'=>'Go入门指南',
                //'url'=>'https://learnku.com/docs/the-way-to-go',
                'url'=>'https://www.cntofu.com/book/14/readme.html',

            ],
            [
                'title'=>'PHP The Right Way[English]',
                'url'=>'https://phptherightway.com',
            ],
            [
                'title'=>'PHP The Right Way[中文简体]',
                'url'=>'http://laravel-china.github.io/php-the-right-way',
            ],
            [
                'title'=>'鸟哥的Linux私房菜-服务器架设篇-第三版',
                'url'=>'https://www.kancloud.cn/wizardforcel/vbird-linux-server-3e/152670'
            ],
            [
                'title'=>'鸟哥的Linux私房菜-基础学习篇-第四版',
                'url'=>'https://www.kancloud.cn/wizardforcel/vbird-linux-basic-4e/152191'
            ],
            [
                'title'=>'如何正确的学习 Node.js',
                'url'=>'https://i5ting.github.io/How-to-learn-node-correctly'
            ],
            [
                'title'=>'开源世界旅行手册',
                'url'=>'https://i.linuxtoy.org/docs/guide/index.html'
            ],
            [
                'title'=>'图说设计模式',
                'url'=>'https://design-patterns.readthedocs.io/zh_CN/latest/index.html'
            ],
            [
                'title'=>'正则表达式30分钟入门教程',
                'url'=>'http://deerchao.net/tutorials/regex/regex.htm'
            ],
            [
                'title'=>'Go Web 编程 ',
                'url'=>'https://github.com/astaxie/build-web-application-with-golang/blob/master/zh/preface.md'
            ],
            [
                'title'=>'七天学会NodeJS',
                'url'=>'http://nqdeng.github.io/7-days-nodejs'
            ],
            [
                'title'=>'PHP 最佳实践',
                'url'=>'https://phpbestpractices.justjavac.com'
            ],
            [
                'title'=>'深入理解Yii2.0',
                'url'=>'http://www.digpage.com'
            ],
            [
                'title'=>'swoole文档及入门教程',
                'url'=>'https://linkeddestiny.gitbooks.io/easy-swoole/content'
            ],
            [
                'title'=>'PHPUnit 中文文档',
                'url'=>'https://phpunit.de/manual/6.5/zh_cn/installation.html'
            ],
            [
                'title'=>'Django book 2.0',
                'url'=>'http://djangobook.py3k.cn/2.0'
            ],
            [
                'title'=>'The Linux Command Line',
                'url'=>'http://kangly.coding.me/TLCL/book',
            ],
            [
                'title'=>'PHP精粹编写高效PHP代码',
                'url'=>'http://kangly.coding.me/03/PHP精粹编写高效PHP代码.html'
            ],
            [
                'title'=>'Python编程-从入门到实践',
                'url'=>'http://kangly.coding.me/04/Python编程-从入门到实践.html'
            ],
            [
                'title'=>'简约之美-软件设计之道',
                'url'=>'http://kangly.coding.me/06/简约之美-软件设计之道.html'
            ],
            [
                'title'=>'怎样解题数学思维的新方法',
                'url'=>'http://kangly.coding.me/07/怎样解题数学思维的新方法.html'
            ],
            [
                'title'=>'啊哈！算法',
                'url'=>'http://kangly.coding.me/08/啊哈算法.html'
            ],
            [
                'title'=>'Node.js实战(第2版)',
                'url'=>'http://kangly.coding.me/09/Node.js实战-第2版.html'
            ],
            [
                'title'=>'算法(第4版)',
                'url'=>'http://kangly.coding.me/10/算法-第4版.html'
            ],
            [
                'title'=>'7天学会PHP',
                'url'=>'http://kangly.coding.me/01/7天学会PHP.html'
            ],
            [
                'title'=>'MySQL索引背后的数据结构及算法原理',
                'url'=>'http://kangly.coding.me/02/MySQL索引背后的数据结构及算法原理.html'
            ],
            [
                'title'=>'3天入门MySQL',
                'url'=>'http://kangly.coding.me/11/3天入门MySQL.html'
            ],
            [
                'title'=>'MySQL-超新手入门',
                'url'=>'http://kangly.coding.me/12/MySQL-超新手入门.html'
            ],
            [
                'title'=>'Mysql设计与优化专题',
                'url'=>'http://kangly.coding.me/13/Mysql设计与优化专题.html'
            ]
        ];

        $this->assign('books_data',$books_data);

        return $this->fetch();
    }
}