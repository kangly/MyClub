<?php

namespace app\admin\service;

use think\facade\Config;

class Image
{
    /**
     * wangEditor编辑器上传本地图片
     * @return \think\response\Json
     */
    public function uploadImages()
    {
        $images = [];
        $errors = [];
        $files = request()->file();
        foreach($files as $file){
            // 移动图片到/public/uploads/images/ 目录下
            $info = $file->move('../public/uploads/images');
            if($info){
               ;
                $path =  Config::get('website_url').'/uploads/images/'.$info->getSaveName();
                array_push($images,$path);
            }else{
                array_push($errors,$file->getError());
            }
        }

        //json输出wangEditor规定的返回数据
        if($errors){
            $msg['errno'] = 1;
            $msg['data'] = $images;
            $msg['msg'] = "上传出错了";
            return json($msg);
        }else{
            $msg['errno'] = 0;
            $msg['data'] = $images;
            return json($msg);
        }
    }

    /**
     * 上传图片并生成缩略图
     * @param $name
     * @return array
     */
    public function uploadFile($name)
    {
        $file = request()->file($name);
        $info = $file->move('../public/uploads/images/');
        if($info){
            return [
                'status' => 'success',
                'info' => 'uploads/images/'.$info->getSaveName()
            ];
        }else{
            return [
                'status' => 'error',
                'info' => $file->getError()
            ];
        }
    }
}