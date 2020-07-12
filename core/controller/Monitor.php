<?php

namespace core\controller;

class Monitor {

    public static function run( $controller, $method, $isMonitor ) {
        if ( config( 'monitor' ) ) {
            if ( $isMonitor ) {
                $redis = redis();
                $key = 'total_'.$controller.'_'.$method;
                $record = $redis->get( $key );
                if ( $record ) {
                    $redis->incr( $key );
                } else {
                    $expireTime = mktime( 23, 59, 59, date( 'm' ), date( 'd' ), date( 'Y' ) );
                    $redis->setex( $key, $expireTime - timestamp( 10 ), 1 );
                }

            }
        }
    }
}