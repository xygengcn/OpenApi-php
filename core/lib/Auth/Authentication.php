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
    public static function AuthDomain($domains = array())
    {

        if (config('mode') == 'dev') {
            return true;
        }
        if (!empty($domains)) {
            if (in_array(getOriginDomain(), $domains)) {
                return 1;
            }
        } else {
            if (getDomain() == getOriginDomain()) {
                return 1;
            }
        }
        error('没有域名权限', 777);
    }

    public static function AuthIP()
    {

        if (config('mode') == 'dev') {
            return true;
        }
        if (getIP() == $_SERVER['SERVER_ADDR']) {
            return true;
        }
        error('没有IP权限', 777);
    }
    public static function AuthSecret()
    {

        if (config('mode') == 'dev') {
            return true;
        }

        if (getDomain() == getOriginDomain()) {
            return true;
        }

        if (isset(header::getheaders()['Token'])) {

            $token = header::getheaders()['Token'];
        } elseif (isset($_COOKIE['token'])) {

            $token = $_COOKIE['token'];
        } elseif (!empty(getParam('token'))) {
            $token = getParam('token');
        } else {

            $token = null;
        }
        $redis = redis();

        if ($device = $redis->get('token_' . $token)) {
            if ($device == getDevice()['device']) {
                return;
            }
            error('没有设备权限', 777);
        }
        error('没有token权限', 777);
    }
}