<?php

/**
* 宝塔接口
*/

namespace app\controller;
use app\model\BT as model;

class BT {

    public function __construct() {
        Auth();
    }

    public function index( $api ) {
        $bt = new model( config( 'bt' )['api'], config( 'bt' )['key'] );
        $data = call_user_func( [$bt, $api] );
        if ( array_key_exists( 'status', $data ) && $data['status'] == false ) {
            error( $data['msg'] );
        }
        response( $data );
    }

}