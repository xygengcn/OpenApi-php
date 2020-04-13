<?php
use \core\controller\Controller as Controller;
use \core\lib\route\route as route;

class App
{
    public static function run()
    {
        //路由参数
        $urlParam = urlParams();
        //函数参数
        $methodParams = [];
        //遍历已定义的路由
        $route = route::run($urlParam);
        if ($route) {
            $params = explode('@', trim($route[0]));
            $controller = $params[0];
            $method = $params[1];
            $methodParams = $route[1];
        } else {
            $count = count($urlParam);
            if ($count == 1 && $urlParam[0] == "") {
                display();
            }
            if (preg_match("/^.*\.html$/", $urlParam[$count - 1])) {
                display($a);
            }
            if ($count > 1) {
                $controller = $urlParam[0];
                $method = $urlParam[1];
                $methodParams = array_splice($urlParam, 2, $count - 2);
            } elseif ($count == 1 && $urlParam[0] != "") {
                $controller = $urlParam[0];
                $method = "index";
            }
        }
        Controller::run($controller, $method, $methodParams);
    }
}