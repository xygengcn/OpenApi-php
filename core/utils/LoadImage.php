<?php
/*
 * @Author: xygengcn
 * @Date: 2020-04-14 19:29:43
 * @Last Modified by: xygengcn
 * @Last Modified time: 2020-04-14 19:29:43
 */
namespace core\utils;

class LoadImage
{
    public function __construct()
    {}

    public static function create()
    {
        static $instance;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    public function load($url)
    {
        header('Content-type:image/png');
        echo file_get_contents($url);
    }
}