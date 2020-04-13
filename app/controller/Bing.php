<?php
namespace app\controller;

/**
 * 请保持文件名和类名一致
 *
 * 组件引用use  \app\model\+名字
 *
 */

class Bing
{
    private $url = "https://cn.bing.com/HPImageArchive.aspx?format=js";

    public function index()
    {
        $str = file_get_contents($this->url . '&idx=0&n=1');
        $str = json_decode($str, true);
        $imgurl = 'http://cn.bing.com' . $str["images"][0]["url"];
        if ($imgurl) {
            header('Content-Type: image/JPEG');@ob_end_clean();@readfile($imgurl);@flush();@ob_flush();
            die();
        } else {
            error('获取图片错误');
        }
    }
    public function week()
    {
        for ($i = 0; $i <= 7; $i++) {
            $contents = $this->url . '&idx=' . "" . $i . "" . '&n=1';
            $str = file_get_contents($contents);
            $str = json_decode($str, true);
            $imgurl[] = 'http://cn.bing.com' . $str["images"][0]["url"];
        }
        response($imgurl);
    }
}