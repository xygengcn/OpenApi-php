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
        $route = "";
        $param = [];
        $count = count($urlParam);
        for ($i = 0; $i < $count; $i++) {
            $route = "/" . implode("/", $urlParam);
            if ($class = self::isRoute($route)) {
                return [$class, $param];
            }
            array_unshift($param, array_pop($urlParam));
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