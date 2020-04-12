<?php
return [

    //时区设置，默认上海
    "timezone" => 'Asia/Shanghai',

    //debug
    "ini" => [
        //打开错误提示
        "display_errors" => "On",
        //显示所有错误
        "error_reporting" => E_ALL,
    ],

    "header" => [
        'Content-Type' => "application/json; charset=utf-8'", //内容类型,编码

        'Access-Control-Allow-Origin' => "*", //*代表允许任何网址请求，跨域设置

        'Access-Control-Allow-Methods' => "POST,GET,OPTIONS,DELETE", // 允许请求的类型

        'Access-Control-Allow-Credentials' => 'true', // 设置是否允许发送 cookies

        'Access-Control-Allow-Headers' => ' Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin', // 设置允许自定义请求头的字段
    ],

];