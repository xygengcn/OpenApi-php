<?php

namespace app\controller;
use \simple_html_dom as simple_html_dom;

class Weibo {

    private $hot_api = 'https://s.weibo.com/ajax/jsonp/gettopsug';
    private $hots_api = 'https://s.weibo.com/top/summary?cate=realtimehot';

    public function hot() {
        $data = get( $this->hot_api );
        $data = substr( $data, 13, strlen( $data )-25 );
        $data = json_decode( $data, true );
        response( $data['data'] );
    }

    public function hots() {

        $dom = new simple_html_dom();
        $data = array();
        $html = curl_fetch( $this->hots_api );
        $dom->load( $html );
        if ( $list = $dom->find( '#pl_top_realtimehot tbody', 0 ) ) {
            $list = $list->find( 'tr' );
            foreach ( $list as $key => $item ) {
                if ( $item->find( '.td-02 a', 0 ) ) {
                    $temp['realpos'] = $key-1;
                    $temp['note'] = $item->find( '.td-02 a', 0 )->innertext;
                    $temp['url'] = 'https://s.weibo.com'.$item->find( '.td-02 a', 0 )->href;
                    if ( $item->find( '.td-02 img', 0 ) ) {
                        $temp['icon'] = 'https:'.$item->find( '.td-02 img', 0 )->src;
                    } else {
                        $temp['icon'] = '';
                    }
                    if ( $item->find( '.td-02 span', 0 ) ) {
                        $temp['num'] = $item->find( '.td-02 span', 0 )->innertext;
                    } else {
                        $temp['num'] = '';
                    }
                    if ( $item->find( '.td-03 i', 0 ) ) {
                        $temp['tag'] = $item->find( '.td-03 i', 0 )->innertext;
                    } else {
                        $temp['tag'] = '';
                    }
                    $data[] = $temp;
                }

            }
        }

        response( $data );

    }
}