<?php
namespace app\controller;

/**
*一言一句
*/

class One {
    private $api_one;

    public function __construct() {
        $redis = redis();
        $api_one = $redis->get( 'api_one' );
        if ( isset( $api_one ) && !empty( $api_one ) ) {
            $this->api_one = json_decode( $api_one, true );
        } else {
            $this->api_one = DB( 'one' )->rand( 1 )->select( 'id', 'tag', 'origin', 'content', 'datetime' );
            $redis->setex( 'api_one', 60, json_encode( $this->api_one ) );
        }
    }

    public function index() {
        response( ...$this->api_one );
    }

    public function get() {
        $data = $this->api_one[0]['content'] . '————' . $this->api_one[0]['origin'];
        _e( "document.write('$data')" );
    }
}