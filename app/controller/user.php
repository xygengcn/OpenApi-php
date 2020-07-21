<?php

namespace app\controller;

/**
 * 企业微信推送
 */

class User
{

    public function __construct()
    {
        Auth('ip');
    }

    public function login()
    {
        $email = getParam('email');
        $password = getParam('password');
        $timeout = 7200;
        if ($email && $password) {
            $user = DB('user')->where('email', '=', $email)->select();
            if ($user == []) {
                error('未注册用户！');
            }
            if (password_verify($password, $user[0]['password'])) {
                $redis = redis();
                $token = password_hash($user[0]['secret'], PASSWORD_BCRYPT);
                if (config('admin') == $email) {
                    $timeout = 7200 * 7200;
                }
                $redis->setex('token_' . $token, $timeout, getDevice()['device']);
                response(['status' => 'success', 'username' => $user[0]['username'], 'token' => $token, 'expire' => $timeout]);
                return;
            } else {
                error('密码不正确');
            }
        } else {
            error('参数缺失');
        }
    }

    public function reg()
    {

        $username = getParam('username');
        $email = getParam('email');
        $password = getParam('password');
        $user = DB('user')->where('email', '=', $email)->select();
        if ($user != []) {
            error('已注册用户');
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        $secret = self::getSecret();
        $result = DB('user')->insert(['username' => $username, 'email' => $email, 'password' => $password, 'secret' => $secret]);
        if ($result > 0) {
            response('注册成功');
        } else {
            error('注册失败');
        }
    }

    private static function getSecret()
    {
        $charid = md5(uniqid(mt_rand(), true));
        $secret = strtoupper(substr($charid, 0, 8)) . substr($charid, 8, 4) . substr($charid, 12, 4) . substr($charid, 16, 4) . strtoupper(substr($charid, 20, 12));
        $secret = str_replace('.', '', $secret);
        return substr(password_hash($secret, PASSWORD_BCRYPT), 10, strlen($secret));
    }
}