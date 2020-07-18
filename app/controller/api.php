<?php

namespace app\controller;

class Api {

    public function __construct() {
        if ( !config( 'monitor' ) ) {
            error( '未开启监控' );
        }
        Auth();
    }

    public function total() {
        $redis = redis();
        $total = [];
        $today_total =[];//今天统计
        $keys = $redis->keys( '*' );
        foreach ( $keys as $key ) {
            if ( strpos( $key, 'total_' ) !== false ) {
                $route = substr( $key, 6 );
                $route = explode( '_', $route );
                $name = '/'.implode( '/', $route );
                $total[$name] = intval( $redis->get( $key ) );
                $today_total[$name] = $total[$name];
            }
        }

        $db = Medoo();
        $data = $db->query( 'SELECT api_name,SUM(total) as total FROM `data` GROUP BY api_name ORDER BY total DESC' )->fetchAll();
        if ( $data ) {
            foreach ( $data as $value ) {
                if ( isset( $total[$value['api_name']] ) ) {
                    $total[$value['api_name']] += $value['total'];
                } else {
                    $total[$value['api_name']] = intval( $value['total'] );
                }

            }
        }

        $history_total =[];//历史统计
        $data = $db->query( "SELECT api_name,total,`date` FROM `data` WHERE `type` != 'total'  ORDER BY api_name,`date`" )->fetchAll();
        if ( $data ) {
            foreach ( $data as $value ) {
               $history_total[$value['api_name']][$value['date']] =$value['total'];
            }
        }
        response( ["total"=>$total,"today" =>$today_total,"history"=>$history_total]);

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