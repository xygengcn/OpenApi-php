<?php
namespace app\controller;

/**
 *Bing每日一图
 *
 */

class Bing
{
    private $url = "https://cn.bing.com/HPImageArchive.aspx?format=js";

    public function __construct()
    {

    }

    public function index()
    {

        $str = file_get_contents($this->url . '&idx=0&n=1');
        $str = json_decode($str, true);
        $imgurl = 'http://cn.bing.com' . $str["images"][0]["url"];
        if ($imgurl) {
            $opt = new \core\utils\LoadImage();
            $opt->create()->load($imgurl);
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