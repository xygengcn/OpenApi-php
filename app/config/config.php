<?php
if ( !defined( 'IN_SYS' ) ) {
    error( '禁止访问' );
}
return [
    'database' =>[
        'database_type' => 'mysql', //只支持mysql
        'server' => 'localhost', //数据库地址
        'port' => 3306, //默认3306
        'database_name' => 'api', //数据库名
        'username' => 'root', //用户名
        'password' => 'root', //密码
        //不了解下面勿动
        'charset' => 'utf8',
        'prefix' => '',
        'logging' => true,
        'socket' => '/tmp/mysql.sock',
        'option' => [
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
        ],
        'command' => [
            'SET SQL_MODE=ANSI_QUOTES',
        ]
    ],
    'header'=>[
        //时区设置，默认上海
        'timezone' => 'Asia/Shanghai',

        //debug
        'ini' => [
            //打开错误提示
            'display_errors' => 'On',
            //显示所有错误
            'error_reporting' => E_ALL,
        ],
        //头部跨域等
        'header' => [
            'Content-Type' => 'application/json; charset=utf-8;application/x-www-form-urlencoded', //内容类型, 编码

            'Access-Control-Allow-Origin' => '*', //*代表允许任何网址请求，跨域设置

            'Access-Control-Allow-Methods' => 'POST,GET,OPTIONS,DELETE', // 允许请求的类型

            'Access-Control-Allow-Credentials' => 'true', // 设置是否允许发送 cookies

            'Access-Control-Allow-Headers' => ' Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin,token', // 设置允许自定义请求头的字段
        ]
    ],
    //环境检查
    'check'=>1,

    //开启监控请求, 需要redis支持
    'monitor'=>0,

    'redis'=>[
        'server' => '127.0.0.1',
        'port' => '6379',
    ]
];