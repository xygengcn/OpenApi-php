<?php
use \core\Exception\SystemException as SystemException;
use \core\lib\route\route as route;

class app
{
    public static function run()
    {
        $a = $_SERVER['REQUEST_URI'];
        $route = route::isRoute($a);
        if ($route) {
            $params = explode('@', trim($route));
            $count = count($params);
            $controller = $params[0];
            $method = $params[1];

        } else {
            $uri = rtrim(preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']), '/');
            $params = explode('/', trim($uri, '/'));
            $count = count($params);
            if ($count == 1 && $params[0] == "") {
                display("index.html");
            }
            if (preg_match("/^.*\.html$/", $params[$count - 1])) {
                display($a);
            }
            if ($count > 1) {
                $controller = $params[0];
                $method = $params[1];
            } elseif ($count == 1 && $params[0] != "") {
                $controller = 'index';
                $method = $params[0];
            } else {
                die();
            }
        }
        $filename = __ROOT__ . '\app\controller\\' . $controller . '.php';
        $controller = '\app\controller\\' . $controller;
        try {
            if (!file_exists($filename)) {
                throw new SystemException('控制器:' . $controller . ' 未定义', 500);
                return;
            }
            include_once $filename;
            if (!class_exists($controller, false)) {
                throw new SystemException('类:' . $controller . ' 未定义', 500);
                return;
            }
            $obj = new \ReflectionClass($controller);
            if (!$obj->hasMethod($method)) {
                throw new SystemException('函数:' . $method . ' 未定义', 500);
                return;
            }

        } catch (SystemException $e) {
            $e->output($e);

        }
        $class = new $controller();
        call_user_func_array(array($class, $method), $params);

    }
}