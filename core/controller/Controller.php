<?php

namespace core\controller;

use \core\Exception\SystemException as SystemException;

class Controller
 {
    private $route = [];
    public static function run( $route ) {

        $controllerName = self::getController( $route );

        $method = self::getMethod( $route );

        $params = $route['params'];

        $filename = __ROOT__ . '\app\controller\\' .$controllerName. '.php';
        $controller = '\app\controller\\' . $controllerName;
        $filename = str_replace( '\\', '/', $filename );
        try {
            if ( !file_exists( $filename ) ) {
                throw new SystemException( '控制器:' . $controller . ' 未定义文件', 404 );
                return;
            }
            include_once $filename;
            if ( !class_exists( $controller, false ) ) {
                throw new SystemException( '控制类:' . $controller . ' 未定义', 404 );
                return;
            }
            $obj = new \ReflectionClass( $controller );
            if ( !$obj->hasMethod( $method ) ) {
                throw new SystemException( '控制函数:' . $method . ' 未定义', 404 );
                return;
            }

        } catch ( SystemException $e ) {
            $e->output( $e );

        }
        $class = new $controller();

        call_user_func_array( array( $class, $method ), $params );

        Monitor::run( $controllerName, $method, $route['controller']['monitor'] );

    }

    private static function getController( $route ) {

        $array = explode( '@', $route['controller']['api'] );

        return strtolower( array_shift( $array ) );
    }

    private static function getMethod( $route ) {
        $array = explode( '@', $route['controller']['api'] );

        return array_pop( $array );
    }
}
