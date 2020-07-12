<?php
namespace app\controller;

/**
* @name：小说接口
* @author：xygengcn
*/

class Novel {
    //解析html句柄
    private $dom = null;
    //书源数据
    private $bookSources = array();

    public function __construct() {
        Auth();
        $this->dom = new \simple_html_dom();
        $this->bookSource();
    }

    public function index() {
        display( 'novel/index.html' );
    }

    //书籍大全

    public function books() {
        $url_arr = array();
        foreach ( $this->bookSources as $key => $item ) {
            array_push( $url_arr, $item['listUrl'] );
        }
        $data = curl_multi_fetch( $url_arr );
        $list = array();
        foreach ( $data as $key => $value ) {
            $this->dom->load( $value );
            if ( $str = $this->dom->find( $this->bookSources[$key]['listRuleDom'] ) ) {
                foreach ( $str as $a ) {
                    array_push( $list, array( 'book' => $a->innertext, 'source' => $a->href, 'api' => $this->urlEnApi( $key, $a->href, 'Novel/book' ) ) );
                }
            }
        }
        response( $list );
    }

    //书籍详细

    public function book( $source = 0 ) {
        if ( func_num_args() == 0 ) {
            error( '参数不完整' );
        } else {
            $url = $this->urlDeApi( func_get_args() );
            $html = curl_fetch( $url );

        }
    }

    //源地址解析成接口地址

    private function urlEnApi( $index, $url, $api ) {
        $str = site_url();
        return $str . "/$api/$index" . parse_url( $url, PHP_URL_PATH );
    }
    //接口地址解析成源地址

    private function urlDeApi( $params = array() ) {
        array_shift( $params );
        return $this->bookSources[$source]['host'] . '/' . implode( '/', $params );
    }

    //书源

    private function bookSource() {
        if ( $json_string = file_get_contents( __ROOT__ . '/app/config/bookSources.json' ) ) {
            $this->bookSources = json_decode( $json_string, true );
        } else {
            error( '书源读取失败' );
        }
    }
}
