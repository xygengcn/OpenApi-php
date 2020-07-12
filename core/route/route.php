<?php

namespace core\route;
/**
* 路由集
*/

class route {

    private $routes = [];

    public function __construct() {
        $this->routes = \core\lib\data\route::route();
    }
    public static function run() {
        $urlParam = urlParams();
        $route = self::runRoutes( $urlParam );
        if ( $route ) {
            return $route;
        }
        return self::createRouters( $urlParam );
    }
    private static function runRoutes( $urlParam ) {
        $route = '';
        $param = [];
        $count = count( $urlParam );
        for ( $i = 0; $i < $count; $i++ ) {
            //循环，[bing, url]=>/bing/url
            $route = '/' . implode( '/', $urlParam );

            if ( $controller = self::isRoute( $route ) ) {

                return ['controller'=>$controller, 'params'=>$param, 'route'=>$route, 'type'=>'api'];
            }
            //提取最后一个元素作为参数
            array_unshift( $param, array_pop( $urlParam ) );
        }
        return false;
    }

    private static function createRouters( $urlParam ) {
        $count = count( $urlParam );
        if ( $count == 0 ) {
            display();
        }
        //默认
        if ( $count == 1 ) {
            $route = '/'.$urlParam[0];
            $controller = [
                'api'=>array_shift( $urlParam ).'@index',
                'monitor'=>true
            ];
            return ['controller'=>$controller, 'params'=>$urlParam, 'route'=>$route, 'type'=>'api'];
        }
        if ( $count >= 2 ) {
            $route = '/'.$urlParam[0].'/'.$urlParam[1];
            $controller = [
                'api'=>array_shift( $urlParam ).'@'.array_shift( $urlParam ),
                'monitor'=>true
            ];
            return ['controller'=>$controller, 'params'=>$urlParam, 'route'=>$route, 'type'=>'api'];
        }
        return false;
    }
    private static function isRoute( $route ) {
        $obj = new self();
        if ( array_key_exists( $route, $obj->routes ) ) {
            return $obj->routes[$route];
        }
    }
}