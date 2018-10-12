<?php

namespace controllers;

use models\Admin;

class LoginController
{
    public function signin()
    {
        view('admin.login.login');
    }

    public function doSignin()
    {
        $model = new Admin;
        $data = $model->login($_POST['username'],md5($_POST['password']));

        if($data!=false) {
            echo json_encode([
                'status' => '200',
                'messages' => '登录成功!',
                'data' => $data,
            ]);
        }else {
            echo json_encode([
                'status' => '404',
                'errormsg' => '登录失败!',
                'data' => $_POST,
            ]);
        }
    }

    public function logout()
    {
        $_SESSION = [];
        header('Location:/login/signin');
    }
}
