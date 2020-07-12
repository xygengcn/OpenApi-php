<?php

namespace app\controller;
use \app\model\Aliyun as Aliyun;

/**
* 邮件推送
*/

class Email {
    public function __construct() {
        Auth();
    }

    public function index() {
        $data = [
            'to'=>'321772514@qq.com',
            'fromName'=>'XY助手',
            'subject'=>'这是一封邮件',
            'html'=>array( 'title'=>'XY助手提醒你！', 'content'=>'注意身体！' ),
        ];
        Aliyun::send( $data );
    }
}
