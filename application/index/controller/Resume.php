<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/1/16
 * Time: 10:48
 */
namespace app\index\controller;

use Dompdf\Dompdf;

/**
 * html to pdf
 * Class Resume
 * @package app\index\controller
 */
class Resume extends Home
{
    public function index()
    {
        return $this->fetch();
    }

    public function download()
    {
        $content = input('content');
        if($content){
            $dompdf = new Dompdf();
            $dompdf->loadHtml($content);
            $dompdf->setPaper('A4','landscape');
            $dompdf->render();
            $dompdf->stream();
        }
    }
}