<?php
namespace core\basic;

class data
{
    public static function getConfigs($configName)
    {
        return require __ROOT__ . '/app/config/' . $configName . ".php";
    }
    public static function getRoutes()
    {
        return require __ROOT__ . "/app/route/route.php";
    }

}