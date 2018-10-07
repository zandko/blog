<?php

namespace models;

use libs\Redis;

class User extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'users';
    protected $fillable = ['user_name', 'user_password', 'user_email', 'user_profile_photo', 'user_rights', 'user_age', 'user_telephone_number', 'user_nickname', 'user_birthday', 'user_level'];

    public function register()
    {
        $redis = Redis::getInstance();

        if ($redis->hexists('email.to.id', $_POST['user_email'])) {
            false;
        }

        $userId = $redis->incr('user:count');

        $value = json_encode([
            'uid' => $userID,
            'email' => $_POST['user_email'],
            'password' => md5($_POST['user_password']),
        ]);

        $redis->setex("user:{$userId}", 300, $value);

        $redis->hset('email.to.id', $_POST['user_email'], $userId);

        $code = rand(1, 99999);

        $name = explode('@', $_POST['email']);
        $from = [
            $_POST['email'],
            $name[0],
        ];
        $message = [
            'title' => '欢迎注册我的博客',
            'content' => "点击以下链接进行激活:</br>点击激活<a href='localhost:9999/uesr/user_active?code={$code}'>http://localhost:9999/user/user_active?code={$code}</a>",
            'from' => $from,
        ];

        $message = json_encode($message);
        $redis->lpush('email', $message);
        echo "注册成功";
    }

    public function login()
    {
        $redis = Redis::getInstance();
        $userId = $redis->hget("email.to.id", $_POST['user_email']);

        if (!$userId) {
            return false;
        }

        $key = "user:$userId";
        $data = json_decode($redis->get($key), true);
        $password = $data['password'];

        if (md5($_POST['user_password']) != $password) {
            echo "密码错误!";
        } else {
            echo "登录成功!";
        }

    }
}
