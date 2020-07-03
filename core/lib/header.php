<?php

class header
 {
    private $config;
    public static function init()
 {
        \core\lib\Check::run();
        $obj = new self();
        $obj->config = config( 'header' );
        date_default_timezone_set( $obj->config['timezone'] );
        self::_header( $obj->config['header'] );
        self::_ini_set( $obj->config['ini'] );

    }
    private static function _header( $config ) {

        foreach ( $config as $key => $val ) {
            header( $key . ':' . $val );
        }

    }
    private static function _ini_set( $config ) {

        foreach ( $config as $key => $val ) {
            ini_set( $key, $val );
        }

    }
    public static function getheaders() {
        foreach ( $_SERVER as $name => $value ) {
            if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
                $headers[str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) )] = $value;
            }
        }
        return $headers;
    }
}