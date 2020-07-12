<?php
use \core\controller\Controller as Controller;
use \core\route\route as route;

class App
 {
    public static function run()
 {
        header::init();

        //遍历已定义的路由
        $route = route::run();

        if ( $route ) {
            Controller::run( $route );
            return true;
        }
        error( '路由错误' );
    }
}