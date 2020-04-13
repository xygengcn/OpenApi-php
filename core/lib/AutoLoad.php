<?php
//根据类名来include文件
class AutoLoad
{
    //找到对应文件就include
    public static function _autoLoad($name)
    {
        $_map = maps();

        $explode = explode('\\', $name);
        $class = end($explode);
        if (isset($_map[$class])) {
            return include_once $_map[$class];
        }
        if ($file = self::filepath($name)) {
            return include_once $file;
        }
        error($name . "文件引入错误");
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
spl_autoload_register('AutoLoad::_autoLoad');