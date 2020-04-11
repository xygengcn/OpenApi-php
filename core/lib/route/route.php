<?php

namespace core\lib\route;

class route
{
    /**
     * 路由集
     */

    private $routes = [];
    private $route = "";

    public function __construct()
    {
        $this->routes = \core\basic\data::getRoutes();
    }
    public static function run($urlParam)
    {
        $route = "/";
        foreach ($urlParam as $key => $val) {
            $route .= $val;
            if ($class = self::isRoute($route)) {
                $param = array_values(array_splice($urlParam, $key + 1, count($urlParam) - $key - 1));
                return [$class, $param];
            }
            $route .= "/";
        }
        return false;

    }
    public static function isRoute($route)
    {
        $obj = new self();
        if (array_key_exists($route, $obj->routes)) {
            return $obj->routes[$route];
        }
    }

}