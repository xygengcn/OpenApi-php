<?php

namespace app\controller;

class Api {

    public function __construct() {
        //Auth();

    }

    public function total() {
        $redis = redis();
        $total = [];
        $keys = $redis->keys( '*' );
        foreach ( $keys as $key ) {
            if ( strpos( $key, 'total_' ) !== false ) {
                $route = substr( $key, 6 );
                $route = explode( '_', $route );
                $name = '/'.implode( '/', $route );
                $total[$name] = intval( $redis->get( $key ) );
            }
        }
        $db = Medoo();
        $data = $db->query( 'SELECT api_name,SUM(total) as total FROM `data` GROUP BY api_name' )->fetchAll();
        if ( $data ) {
            foreach ( $data as $value ) {
                if ( isset( $total[$value['api_name']] ) ) {
                    $total[$value['api_name']] += $value['total'];
                } else {
                    $total[$value['api_name']] = intval( $value['total'] );
                }

            }
        }
        response( $total );

    }

    public function task() {

        $redis = redis();
        $total = [];
        $keys = $redis->keys( '*' );
        foreach ( $keys as $key ) {
            if ( strpos( $key, 'total_' ) !== false ) {
                $route = substr( $key, 6 );
                $route = explode( '_', $route );
                $item['api_name'] = '/'.implode( '/', $route );
                $item['total'] = $redis->get( $key );
                $item['type'] = $route[0];
                $item['date'] = date( 'Y-m-d' );
                $total[] = $item;
            }
        }
        if ( DB( 'data' )->insert( ...$total ) ) {
            response( 'success' );
        } else {
            error( 'error' );
        }

    }
}