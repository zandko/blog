<?php

namespace controllers;

use libs\Mail;
use libs\Redis;

class MailController
{
    public function send()
    {
        $redis = Redis::getInstance();
        $mail = new Mail;

        ini_set('default_socket_timeout', -1);

        echo "开始发送邮件!";
        while (true) {
            $data = $redis->brpop('email', 0);
            $message = json_decode($data[1], true);
            $mail->send($message['title'], $message['content'], $message['from']);

            echo "发送邮件成功,继续发送下一个\r\n";
        }
    }
}
