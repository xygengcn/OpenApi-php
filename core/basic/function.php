<?php

/**
 * json输出
 */
function response($data, $code = 200)
{
    if (is_null($data)) {
        $error = "data is null";
        error($error);
    }
    if ($code == 200 && is_string($data) || is_numeric($data) || is_float($data) || is_int($data)) {
        echo json_encode(array("code" => $code, "data" => $data));
        return;
    }
    if ($code == 200 && is_array($data)) {
        if (count($data) == 1) {
            echo json_encode(array("code" => $code, "data" => $data[0]));
        } else {
            echo json_encode(array("code" => $code, "data" => $data));
        }
        return;
    }
    if ($code == 200 && is_object($data)) {
        echo json_encode(array("code" => $code, "msg" => "data is object", $data));
        return;
    }
    echo json_encode(array("code" => $code, "data" => $data));

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
function error($error = "未知错误", $code = 404)
{
    die(json_encode(array("code" => $code, "error" => $error)));
}

/**
 * 时间戳
 */
function timestamp()
{
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
function str_prefix($str, $n = 1, $char = " ")
{
    for ($x = 0; $x < $n; $x++) {$str = $char . $str;}
    return $str;
}
function str_suffix($str, $n = 1, $char = " ")
{
    for ($x = 0; $x < $n; $x++) {$str = $str . $char;}
    return $str;
}

//获取服务器域名加协议
function site_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
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
    return preg_replace('/[-\',\.\$\^\*-+!\?\/@"\|\\()]/i', "", $str);
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