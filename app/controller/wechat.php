<?php
namespace app\controller;
/**
* 企业微信推送
*
* @Author XYGENGCN
* @Blog https://xygeng.cn
* @DateTime 2020-07-03
*/

class Wechat
 {
    private $access_token = '';
    private $access_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken';
    private $send_url = 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=';

    public function __construct() {
        Auth();
        $this->getAccessToken();
    }

    private function getAccessToken() {
        $redis = redis();
        $access_token = $redis->get( 'Wechat_Access_Token' );
        if ( $access_token ) {
            $this->access_token = $access_token;
        } else {
            $url = $this->access_token_url.'?corpid='.config( 'wechat' )['corpid'].'&corpsecret='.config( 'wechat' )['corpsecret'];
            $result = json_decode( get( $url ) );
            $this->access_token = $result->access_token;
            $redis->setex( 'Wechat_Access_Token', $result->expires_in, $result->access_token );
        }
    }

    public function index() {
        response( 'success' );
    }

    public function send() {
        $message = array();
        $message['to'] = '@all';
        $message['content'] = getParam( 'content' );
        $message['type'] = 'text';
        self::post( $message );
    }

    public function post( $url, $data ) {
        $url = $this->send_url.$this->access_token;
        $data = $this->message( $message );
        $result = json_decode( post( $url, $data ) );
        response( $result );
    }

    private function message( $data = array() ) {
        return array(
            'touser'=>$data['to']?$data['to']:'@all',
            'msgtype'=>$data['type']?$data['type']:'text',
            'agentid'=>config( 'wechat' )['agentid'],
            'text'=>array(
                'content'=>$data['content']?$data['content']:'Hello World'
            ),
            'safe'=>0,
            'enable_id_trans'=>0,
            'enable_duplicate_check'=> 1,
            'duplicate_check_interval'=> 1800
        );
    }
}
