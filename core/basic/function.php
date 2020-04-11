<?php

function response($data, $code = 200)
{
    if (is_null($data)) {
        $error = "data is null";
        error($error);
    }
    if ($code == 200 && is_string($data) || is_numeric($data) || is_float($data) || is_int($data) && is_array($data)) {
        echo json_encode(array("code" => $code, "data" => $data));
        return;
    }
    if ($code == 200 && is_object($data)) {
        echo json_encode(array("code" => $code, "msg" => "data is object", $data));
        return;
    }
    echo json_encode(array("code" => $code, "data" => $data));

}
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
function error($error = "未知错误", $code = 404)
{
    die(json_encode(array("code" => $code, "error" => $error)));
}

function config($configName)
{

    return \core\basic\data::getConfigs($configName);
}

/**
 * 数据库操作
 */
function DB($table = null)
{
    if (isset($table) && !empty($table)) {
        if (is_string($table)) {
            return new \core\lib\db\DB($table);
        } else {
            error("数据库表格式不对");
        }
    }
    error("数据库表为空");

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
/**
 *显示界面，需要在public新建
 */
function display($html)
{
    $url = site_url() . '/' . $html;
    $file = __ROOT__ . '\public\\' . $html;
    if (file_exists($file)) {
        header("Location:" . $url);exit;
    } else {
        header("Location:" . '\public\error\404.html');exit;
    }
}
//获取域名加协议
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