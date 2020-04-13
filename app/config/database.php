<?php
return [
    "database_type" => "mysql", //只支持mysql
    "server" => "localhost", //数据库地址
    "port" => 3306, //默认3306
    "database_name" => "api", //数据库名
    "username" => "root", //用户名
    "password" => "root", //密码
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
    ],
];