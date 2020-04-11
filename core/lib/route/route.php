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

    public static function isRoute($route)
    {
        $obj = new self();
        $obj->route = rtrim($route, "/");
        if (array_key_exists($obj->route, $obj->routes)) {
            return $obj->routes[$obj->route];
        }
    }

}