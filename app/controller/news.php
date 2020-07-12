<?php

namespace app\controller;
use \simple_html_dom as simple_html_dom;
/**
*
* @Author XYGENGCN
* @Blog https://xygeng.cn
* @DateTime 2020-07-03
*/

class news
 {
    public function __construct() {
        Auth();
        $this->dom = new simple_html_dom();
    }

    public function index() {

        response( $this->news_flash() );
    }

    public function more( $date, $page ) {
        $url = "https://a.jiemian.com/index.php?m=lists&a=ajaxNews&cid=4&page=$page&date=$date";
        $news = array();
        $news['page'] = $page;
        $news['date'] = $date;
        $html = curl_fetch( $url );
        $html = json_decode( rtrim( ltrim( $html, '(' ), ')' ) )->rst;
        $this->dom->load( $html );
        if ( $lists = $this->dom->find( '.item-news' ) ) {
            foreach ( $lists as $item ) {
                $temp['datetime'] = $item->first_child()->innertext;
                $temp['title'] = $item->find( 'p a', 0 )->innertext;
                $temp['url'] = $item->find( 'p a', 0 )->href;
                $temp['content'] = $item->find( 'p', 0 )->innertext;
                $temp['text'] = preg_replace( '/\\t*【.*】/', '', $temp['content'] );
                $news['news'][] = $temp;
            }
        }
        response( $news );
    }

    private function news_flash() {
        $news = array();
        $html = curl_fetch( 'https://www.jiemian.com/lists/4.html' );
        $this->dom->load( $html );
        if ( $list = $this->dom->find( '#lists', 0 ) ) {
            $news['page'] = 1;
            $news['date'] = $list->first_child()->innertext;
            $lists = $list->find( '.item-news' );
            foreach ( $lists as $item ) {
                $temp['datetime'] = $item->first_child()->innertext;
                $temp['title'] = $item->find( 'p a', 0 )->innertext;
                $temp['url'] = $item->find( 'p a', 0 )->href;
                $temp['content'] = $item->find( 'p', 0 )->innertext;
                $temp['text'] = preg_replace( '/\\t*【.*】/', '', $temp['content'] );
                $news['news'][] = $temp;
            }
        }
        if ( $more = $this->dom->find( '.load-view .load-more', 0 ) ) {
            $news['more']['date'] = $more->getAttribute( 'date' );
            $news['more']['page'] = $more->getAttribute( 'page' );
        }
        return $news;
    }
}
