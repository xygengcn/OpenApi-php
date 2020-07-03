<?php
/*
* @Author: xygengcn
* @Date: 2020-04-14 20:48:02
* @Last Modified by: xygengcn
* @Last Modified time: 2020-04-14 20:50:28
*
* 身份验证
*/
namespace core\lib\Auth;
use \header as header;

class Authentication
 {
    public static function AuthDomain( $domains = array() ) {
        if ( !empty( $domains ) ) {
            if ( in_array( getOriginDomain(), $domain ) ) {
                return 1;
            }
        } else {
            if ( getDomain() == getOriginDomain() ) {
                return 1;
            }
        }
        error( '没有权限', 500 );
    }
    public static function AuthSecret() {

        $token = isset( header::getheaders()['Token'] );
        if ( $token && config( 'secret' ) ) {
            $token = header::getheaders()['Token'];
            if ( config( 'secret' ) ===  $token ) {
                return true;
            }
        }
        error( '没有权限', 500 );
    }

}