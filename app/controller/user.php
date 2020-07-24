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
        $code = getParam('code');
        $timeout = 7200;
        if (config('admin') == $email) {
            $timeout = 7200 * 7200;
        }
        if ($email && $password) {
            $user = DB('user')->where('email', '=', $email)->select();
            if ($user == []) {
                error('未注册用户！');
            }
            if (password_verify($password, $user[0]['password'])) {
                $redis = redis();
                $token = password_hash($user[0]['secret'], PASSWORD_BCRYPT);
                $redis->setex('token_' . $token, $timeout, getDevice()['device']);
                response(['status' => 'success', 'username' => $user[0]['username'], 'email' => $email, 'token' => $token, 'expire' => $timeout]);
                return;
            } else {
                error('密码不正确');
            }
        } else if ($code && $email) {
            $redis = redis();
            if ($redis->hKeys('verify_' . $code)) {
                $user = $redis->hMget('verify_' . $code, ['username', 'token']);
                response(['status' => 'success', 'username' => $user['username'], 'email' => $email,  'token' => $user['token'], 'expire' => $timeout]);
            } else {
                error('安全码过期');
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
        if (empty($username) || empty($email) || empty($password)) {
            error("参数不完整");
        }
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

    public function logout()
    {
        $token = getToken();
        if ($token) {
            $redis = redis();
            if ($redis->get('token_' . $token)) {
                $redis->del('token_' . $token);
            }
            response("success");
            return;
        }
        error("error");
    }

    public function verify()
    {
        $email = getParam('email');
        $username = getParam('username');
        $password = getParam('password');
        $token = getToken();
        if ($email && $password) {
            $user = DB('user')->where('email', '=', $email)->select();
            if ($user == []) {
                error('未注册用户！');
            }
            if (password_verify($password, $user[0]['password'])) {
                $verify_code = rand(1000, 9999);
                $redis = redis();
                $redis->hMset('verify_' . $verify_code, ['username' =>  $username, 'token' => $token]);
                $redis->expire('verify_' . $verify_code, 30);
                response(["code" => $verify_code, "email" => $email, 'username' =>  $username, 'token' => $token]);
                return;
            } else {
                error('验证错误');
            }
        } else {
            error('参数缺失');
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