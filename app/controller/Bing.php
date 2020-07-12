<?php
/* File Info
* Author: xygengcn
* CreateTime: 2020/4/12 下午3:27:00
* LastEditor: xygengcn
* ModifyTime: 2020/4/14 下午7:26:11
* Description:
*/

/**
*Bing每日一图
*
*/
namespace app\controller;

class Bing
 {
    private $url = 'https://cn.bing.com/HPImageArchive.aspx?format=js';

    public function index()
 {
        $redis = redis();
        $api_bing_url = $redis->get( 'api_bing_url' );
        if ( isset( $api_bing_url ) && !empty( $api_bing_url ) ) {
            $imgurl = $api_bing_url;
        } else {
            $str = file_get_contents( $this->url . '&idx=0&n=1' );
            $str = json_decode( $str, true );
            $imgurl = 'http://cn.bing.com' . $str['images'][0]['url'];
            $expireTime = mktime( 23, 59, 59, date( 'm' ), date( 'd' ), date( 'Y' ) );
            $redis->setex( 'api_bing_url', $expireTime - timestamp( 10 ), $imgurl );
        }
        if ( $imgurl ) {
            $opt = new \core\utils\LoadImage();
            $opt->create()->load( $imgurl );
        } else {
            error( '获取图片错误' );
        }
    }

    public function url() {
        $redis = redis();
        $api_bing_url = $redis->get( 'api_bing_url' );
        if ( isset( $api_bing_url ) && !empty( $api_bing_url ) ) {
            $imgurl = $api_bing_url;
        } else {
            $str = file_get_contents( $this->url . '&idx=0&n=1' );
            $str = json_decode( $str, true );
            $imgurl = 'http://cn.bing.com' . $str['images'][0]['url'];
            $expireTime = mktime( 23, 59, 59, date( 'm' ), date( 'd' ), date( 'Y' ) );
            $redis->setex( 'api_bing_url', $expireTime - timestamp( 10 ), $imgurl );
        }
        if ( $imgurl ) {
            response( $imgurl );
        } else {
            error( '获取图片错误' );
        }
    }

    public function week()
 {
        $redis = redis();
        $lLen = $redis->lLen( 'api_bing_week' );
        if ( $lLen != 0 ) {
            $bingWeek = $redis->lrange( 'api_bing_week', 0, -1 );
        } else {
            for ( $i = 0; $i <= 7; $i++ ) {
                $contents = $this->url . '&idx=' . '' . $i . '' . '&n=1';
                $str = file_get_contents( $contents );
                $str = json_decode( $str, true );
                $bingWeek[] = 'http://cn.bing.com' . $str['images'][0]['url'];
                $redis->lpush( 'api_bing_week', 'http://cn.bing.com' . $str['images'][0]['url'] );
            }
            $expireTime = mktime( 23, 59, 59, date( 'm' ), date( 'd' ), date( 'Y' ) );
            $redis->expireAt( 'api_bing_week', $expireTime );
        }
        response( $bingWeek );
    }
}
