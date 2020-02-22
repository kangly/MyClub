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
     * @param int $width
     * @param int $height
     * @return string
     */
    public function uploadFile($name,$width=200,$height=200)
    {
        $file = request()->file($name);
        if($file){
            $filePaths = '../public/uploads/images/';
            $info = $file->move($filePaths);
            if($info){
                $imgPath = 'uploads/images/'.$info->getSaveName();
                $image = \think\Image::open($imgPath);
                $thumb_path = 'uploads/images/'.date('Ymd').'/thumb_'.$info->getFilename();
                $image->thumb($width,$height,\think\Image::THUMB_CENTER)->save($thumb_path);
                $data['img'] = $imgPath;
                $data['thumb_img'] = $thumb_path;
                return $data;
            }else{
                return $file->getError();
            }
        }else{
            return 'empty';
        }
    }
}