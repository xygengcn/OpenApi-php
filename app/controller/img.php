<?php
namespace app\controller;

/**
* 图片接口
* 远程图片转base
*
*/

namespace app\controller;

class Img {
    public function index() {
        response( 'success' );
    }

    public function base64() {

        $url = getParam( 'url' );
        if ( !$url ) {
            error( '没有参数' );
        }
        $type = getimagesize( $url );
        //取得图片的大小，类型等
        $content = file_get_contents( $url );
        $file_content = chunk_split( base64_encode( $content ) );
        //base64编码
        switch( $type[2] ) {
            //判读图片类型
            case 1:$img_type = 'gif';
            break;
            case 2:$img_type = 'jpg';
            break;
            case 3:$img_type = 'png';
            break;
        }
        $imgBase64 = 'data:image/'.$img_type.';base64,'.$file_content;
        //合成图片的base64编码
        response( array(
            'url'=>$url,
            'base64'=>$imgBase64
        ) );
    }
}
