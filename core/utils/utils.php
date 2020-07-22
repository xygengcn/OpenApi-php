<?php

/**
 * json输出
 */

function response($data, $code = 200)
{
    if (is_null($data)) {
        $error = 'data is null';
        error($error);
    }
    if ($code == 200 && is_string($data) || is_numeric($data) || is_float($data) || is_int($data)) {
        echo json_encode(array('code' => $code, 'data' => $data));
        return;
    }
    if ($code == 200 && is_array($data)) {

        echo json_encode(array('code' => $code, 'data' => $data));
        return;
    }
    if ($code == 200 && is_object($data)) {
        echo json_encode(array('code' => $code, 'data' => $data));
        return;
    }
    echo json_encode(array('code' => $code, 'data' => $data));
}
/**
 * 输出
 */

function _e($data)
{

    if (is_string($data) || is_numeric($data) || is_float($data) || is_int($data)) {
        echo $data;
        return;
    }
    if (is_array($data)) {
        echo json_encode($data);
        return;
    }
    if (is_object($data)) {
        var_dump($data);
        return;
    }
    echo $data;
}
/**
 * 报错输出
 */

function error($error = '未知错误', $code = 404)
{
    die(json_encode(array('code' => $code, 'error' => $error)));
}

/**
 * 时间戳
 */

function timestamp($num = 13)
{
    if ($num == 10) {
        return time();
    }
    list($s1, $s2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}
/**
 * 加引号
 */

function str_rl($str, $n = 1, $char = "'")
{
    return str_suffix(str_prefix($str, $n, $char), $n, $char);
}

function str_prefix($str, $n = 1, $char = ' ')
{
    for ($x = 0; $x < $n; $x++) {
        $str = $char . $str;
    }
    return $str;
}

function str_suffix($str, $n = 1, $char = ' ')
{
    for ($x = 0; $x < $n; $x++) {
        $str = $str . $char;
    }
    return $str;
}

function getParam($key)
{
    if (@$_COOKIE[$key]) {
        return $_COOKIE[$key];
    }
    if (@$_GET[$key]) {
        return $_GET[$key];
    }
    if (@$_POST[$key]) {
        return $_POST[$key];
    }
    if (@$_FILES[$key]) {
        return $_FILES[$key];
    }
    return null;
}

//获取服务器域名加协议

function site_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $domainName = $_SERVER['HTTP_HOST'];
    return $protocol . $domainName;
}
//获取网址参数数组

function urlParams()
{
    $urlParam = rtrim(preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']), '/');
    return explode('/', trim($urlParam, '/'));
}
//去掉特殊符号

function str_clean($str)
{
    return preg_replace('/[-\', \.\$\^\*-+!\?\/@"\|\\()]/i', "", $str);
}

//获取来源域名
function getOriginDomain()
{
    if (isset($_SERVER["HTTP_REFERER"])) {
        return parse_url($_SERVER["HTTP_REFERER"])['host'];
    } else {
        return "";
    }
}
function getDomain()
{
    return $_SERVER['HTTP_HOST'];
}

//单条抓取
function curl_fetch($url, $cookie = '', $referer = '', $timeout = 10, $ishead = 0)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_ACCEPT_ENCODING, 'gzip,deflate');
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    if ($referer) {
        curl_setopt($curl, CURLOPT_REFERER, $referer);
    }
    $ssl = substr($url, 0, 8) == 'https://' ? true : false;
    if ($ssl) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    }
    $res = curl_exec($curl);
    $encode = mb_detect_encoding($res, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
    $str_encode = mb_convert_encoding($res, 'UTF-8', $encode);
    curl_close($curl);
    return $str_encode;
}
function curl_multi_fetch($urlarr = array(), $timeout = 10)
{
    $result = $res = $ch = array();
    $nch = 0;
    $mh = curl_multi_init();
    foreach ($urlarr as $url) {
        $ch[$nch] = curl_init();
        $ssl = substr($url, 0, 8) == 'https://' ? true : false;
        if ($ssl) {
            curl_setopt_array($ch[$nch], array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_ACCEPT_ENCODING => 'gzip,deflate',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            ));
        } else {
            curl_setopt_array($ch[$nch], array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_ACCEPT_ENCODING => 'gzip,deflate',
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            ));
        }
        curl_multi_add_handle($mh, $ch[$nch]);
        ++$nch;
    }
    do {
        $mrc = curl_multi_exec($mh, $running);
    } while (CURLM_CALL_MULTI_PERFORM == $mrc);

    while ($running && $mrc == CURLM_OK) {
        if (curl_multi_select($mh, 0.5) > -1) {
            do {
                $mrc = curl_multi_exec($mh, $running);
            } while (CURLM_CALL_MULTI_PERFORM == $mrc);
        }
    }
    if ($mrc != CURLM_OK) {
        error_log("CURL Data Error");
    }
    $nch = 0;
    foreach ($urlarr as $node) {
        if (($err = curl_error($ch[$nch])) == '') {
            $res[$nch] = curl_multi_getcontent($ch[$nch]);
            $encode = mb_detect_encoding($res[$nch], array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            $str_encode = mb_convert_encoding($res[$nch], 'UTF-8', $encode);
            $result[] = $str_encode;
        } else {
            error_log("curl error");
        }
        curl_multi_remove_handle($mh, $ch[$nch]);
        curl_close($ch[$nch]);
        ++$nch;
    }
    curl_multi_close($mh);
    return $result;
}

//星期几
function weekday($format = "星期w")
{

    $weekdays = ['日', '一', '二', '三', '四', '五', '六'];

    return str_replace('w', $weekdays[date('w')], $format);
}

//获取设备
function getDevice()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $reg = "/(?<=\()[^\)]+/";
    preg_match($reg, $user_agent, $result);
    $device['agent'] = $user_agent;
    $device['device'] = $result[0];
    $device['os'] = explode(";", $result[0]);
    foreach ($device['os'] as $key => $item) {
        $device['os'][$key] = trim($item);
    }
    return $device;
}

//获取ip
function getIP()
{

    static $ip = '';

    $ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {

        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {

        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {

        foreach ($matches[0] as $xip) {

            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {

                $ip = $xip;

                break;
            }
        }
    }

    return $ip;
}