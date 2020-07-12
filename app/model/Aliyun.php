<?php

namespace app\model;

class Aliyun {

    private static $aliyun_region = [
        'hangzhou'=>array( 'api'=>'https://dm.aliyuncs.com/', 'version'=>'2015-11-23', 'region'=>'cn-hangzhou' ),
        'singapore'=>array( 'api'=>'https://dm.ap-southeast-1.aliyuncs.com/', 'version'=>'2017-06-22', 'region'=>'ap-southeast-1' ),
        'sydney'=>array( 'api'=>'https://dm.ap-southeast-2.aliyuncs.com/', 'version'=>'2015-11-23', 'region'=>'ap-southeast-2' ),
    ];

    public static function send($param) {

        $data =[
            "to"=>$param["to"],
            "fromName"=>$param["fromName"],
            "subject"=>$param["subject"],
            "content"=>self::html(array("title"=>$param["html"]["title"],"content"=>$param["html"]["content"])),
        ];
        self::aliyun($data);
    }

    private static function aliyun( $param ) {

        $config =config("aliyunMail");
        $api =self::$aliyun_region[$config['region']]['api'];
        // 重新组合为阿里云所使用的参数
        $data = array(
            'Action' => 'SingleSendMail', // 操作接口名
            'AccountName' => $config['accountName'], // 发件地址
            'ReplyToAddress' => 'true', // 回信地址
            'AddressType' => 1, // 地址类型
            'ToAddress' => $param['to'], // 收件地址
            'FromAlias' => $param['fromName'], // 发件人名称
            'Subject' => $param['subject'], // 邮件标题
            'HtmlBody' => $param['content'], // 邮件内容
            'Format' => 'JSON', // 返回JSON
            'Version' =>self::$aliyun_region[$config['region']]['version'], // API版本号
            'AccessKeyId' => $config['AccessKeyID'], // Access Key ID
            'SignatureMethod' => 'HMAC-SHA1', // 签名方式
            'Timestamp' => gmdate( 'Y-m-d\TH:i:s\Z' ), // 请求时间
            'SignatureVersion' => '1.0', // 签名算法版本
            'SignatureNonce' => md5( time() ), // 唯一随机数
            'RegionId' => self::$aliyun_region[$config['region']]['region'] // 机房信息
        );
        // 请求签名
        $data['Signature'] = self::sign( $data, $config['AccessKeySecret'] );
        // 初始化Curl
        $ch = curl_init();
        // 设置为POST请求
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        // 请求地址
        curl_setopt( $ch, CURLOPT_URL,$api);
        // 返回数据
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        // 提交参数
        curl_setopt( $ch, CURLOPT_POSTFIELDS, self::getPostHttpBody( $data ) );
        // 关闭ssl验证
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        // 执行请求
        $result = curl_exec( $ch );
        // 获取错误代码
        $errno = curl_errno( $ch );
        // 获取错误信息
        $error = curl_error( $ch );
        // 获取返回状态码
        $httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        // 关闭请求
        curl_close( $ch );
        // 成功标识
        $flag = TRUE;
        // 如果失败
        if ( $errno ) {
            // 设置失败
            $flag = FALSE;
            error( '邮件发送失败, 错误代码：' . $errno . '，错误提示: ' . $error . PHP_EOL );
        }
        // 如果失败
        if ( 400 <= $httpCode ) {
            // 设置失败
            $flag = FALSE;
            // 尝试转换json
            if ( $json = json_decode( $result ) ) {
                error( '邮件发送失败，错误代码：' . $json->Code . '，错误提示：' . $json->Message . PHP_EOL );
            } else {
                error( '邮件发送失败, 请求返回HTTP Code：' . $httpCode . PHP_EOL );
            }
        }
        // 返回结果
        return $flag;
    }

    /**
    * 阿里云签名
    *
    * @static
    * @access private
    *
    * @param $param
    * @param $accesssecret
    *
    * @return string
    */

    private static function sign( $param, $accesssecret ) {
        // 参数排序
        ksort( $param );
        // 组合基础
        $stringToSign = 'POST&' . self::percentEncode( '/' ) . '&';
        // 临时变量
        $tmp = '';
        // 循环参数列表
        foreach ( $param as $k => $v ) {
            // 组合参数
            $tmp .= '&' . self::percentEncode( $k ) . '=' . self::percentEncode( $v );
        }
        // 去除最后一个&
        $tmp = trim( $tmp, '&' );
        // 组合签名参数
        $stringToSign = $stringToSign . self::percentEncode( $tmp );
        // 数据签名
        $signature = base64_encode( hash_hmac( 'sha1', $stringToSign, $accesssecret . '&', TRUE ) );
        // 返回签名
        return $signature;
    }

    /**
    * 阿里云签名编码转换
    *
    * @access private
    *
    * @param $val
    *
    * @return string|string[]|null
    */

    private static function percentEncode( $val ) {
        // URL编码
        $res = urlencode( $val );
        // 加号转换为%20
        $res = preg_replace( '/\+/', '%20', $res );
        // 星号转换为%2A
        $res = preg_replace( '/\*/', '%2A', $res );
        // %7E转换为~
        $res = preg_replace( '/%7E/', '~', $res );
        return $res;
    }

    /**
    * 阿里云请求参数组合
    *
    * @access private
    *
    * @param $param
    *
    * @return bool|string
    */
    private static function getPostHttpBody( $param ) {
        // 空字符串
        $str = '';
        // 循环参数
        foreach ( $param as $k => $v ) {
            // 组合参数
            $str .= $k . '=' . urlencode( $v ) . '&';
        }
        // 去除第一个&
        return substr( $str, 0, -1 );
    }

    /**
    * 生成模板
    */

    private static function html( $data ) {

        $html = '<table style="width:99.8%;height:99.8%"><tbody><tr><td style="background:#fafafa"><div style="border-radius:10px;font-size:13px;color:#555;width:666px;font-family:\'Century Gothic\',\'Trebuchet MS\',\'Hiragino Sans GB\',\'微软雅黑\',\'Microsoft Yahei\',Tahoma,Helvetica,Arial,SimSun,sans-serif;margin:50px auto;border:1px solid #eee;max-width:100%;background:#fff repeating-linear-gradient(-45deg,#fff,#fff 1.125rem,transparent 1.125rem,transparent 2.25rem);box-shadow:0 1px 5px rgba(0,0,0,.15)"><div style="width:100%;background:#fff;text-align:center;color:#fff;border-radius:10px 10px 0 0;background-image:-moz-linear-gradient(-180deg,#ccc,#666,#333,#000);background-image:-webkit-linear-gradient(-180deg,#ccc,#666,#333,#000);height:66px"><p style="font-size:18px;word-break:break-all;padding:23px 32px;margin:0;background-color:hsla(0,0%,100%,.4);border-radius:10px 10px 0 0">{title}</p></div><div style="margin:40px auto;width:90%">{content}</div></div></td></tr></tbody></table>';
        return str_replace( array( '{title}', '{content}' ), array( trim( $data['title'] ), trim( $data['content'] ) ), $html );
    }
}