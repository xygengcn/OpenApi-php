<?php

namespace core\utils;

class Http {
    public static function get( $url, $headers = [] ) {
        // 初始化
        $curl = curl_init();
        // 设置url路径
        curl_setopt( $curl, CURLOPT_URL, $url );
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
        // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt( $curl, CURLOPT_BINARYTRANSFER, true ) ;
        // 添加头信息
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt( $curl, CURLINFO_HEADER_OUT, true );
        // 不验证SSL
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        // 执行
        $data = curl_exec( $curl );
        // 打印请求头信息
        // echo curl_getinfo( $curl, CURLINFO_HEADER_OUT );
        // 关闭连接
        curl_close( $curl );
        // 返回数据
        return $data;
    }

    public static function post( $url, $data, $headers = [] ) {
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;
        //请求url地址
        $params[CURLOPT_HEADER] = FALSE;
        //是否返回响应头信息
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        $params[CURLOPT_RETURNTRANSFER] = true;

        $params[CURLOPT_HTTPHEADER] = $headers;
        //是否将结果返回
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = json_encode($data);
        curl_setopt_array( $ch, $params );
        //传入curl参数
        $content = curl_exec( $ch );
        //执行
        curl_close( $ch );
        //关闭连接
        return $content;
    }
}