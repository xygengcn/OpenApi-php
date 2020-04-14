<?php
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