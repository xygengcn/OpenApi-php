<?php
//根据类名来include文件
class AutoLoad
{
    //找到对应文件就include
    public static function _autoLoad($name)
    {
        $_map = require __ROOT__ . '/core/map.php';
        $explode = explode('\\', $name);
        $class = $explode[count($explode) - 1];
        if (isset($_map[$class])) {
            return include_once $_map[$class];
        }
        $file = self::filepath($name);
        if ($file) {

            include_once $file;
            return 0;
        }
    }
    public static function filepath($name, $ext = '.php')
    {
        if (!$ext) {
            $ext = '.php';
        }
        if (self::checkstr($name)) {
            $explode = explode('\\', $name);
            $name = str_replace('__', '\\', $explode[count($explode) - 1]);
            $file = $name . $ext;
            $path = __ROOT__ . '\\' . $file;
        } else {
            $file = $name . $ext;
            $path = __ROOT__ . '\\' . $file;
        }

        if (file_exists($path)) {

            return $path;
        }
        return null;
    }
    private static function checkstr($str)
    {
        $needle = "__"; //判断是否包含a这个字符
        $tmparray = explode($needle, $str);
        if (count($tmparray) > 1) {
            return true;
        } else {
            return false;
        }
    }
}
spl_autoload_register($namespace . 'AutoLoad::_autoLoad');