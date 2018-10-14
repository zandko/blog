<?php

namespace controllers;

use libs\Image;

class UploadController
{
    public function uploadImg()
    {
        $file = $_FILES['file'];
        $image = new Image($file);

        $image->thumb = array(
            'is_thumb' => 1,
            'width' => 200,
            'height' => 200,
        );

        $ret = $image->save('/home/zan/project/Practice/public/uploads/article/');
        $_ret = substr($ret, strpos($ret, '/') + 33);

        if ($_ret != false) {
            echo json_encode([
                'code' => 0,
                'msg' => '',
                'data' => [
                    'src' => $_ret,
                ],
            ]);

        } else {
            echo json_encode([
                'code' => 1,
                'msg' => '上传失败!',
                'data' => [
                    'src' => $_ret,
                ],
            ]);
        }

    }

}
