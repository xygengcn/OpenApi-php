<?php
namespace core\lib\data;

class config
{

    public static function config($configName)
    {
        $configs =require __ROOT__ . '/app/config/config.php';
        return $configs[$configName];
    }
}