<?php

namespace libs;

/**
 * @desc：图片上传类
 * 1、自动验证文件是表单提交的文件还是base64流提交的文件
 * 2、验证图片类型是否合法
 * 3、验证图片尺寸是否合法
 * 4、验证图片大小是否合法
 * 5、支持获取图片信息功能
 * 6、支持缩放功能
 * 7、支持裁剪功能
 * 8、支持缩略图功能
 */
class Image
{
    private $file;
    private $type;
    public $suffix = array();
    public $measure = array();
    public $size = array();
    public $scale = array(
        'is_scale' => 0,
        'ratio' => 1,
    );
    public $crop = array(
        'is_crop' => 0,
        'width' => 0,
        'height' => 0,
    );
    public $thumb = array(
        'is_thumb' => 0,
        'width' => 0,
        'height' => 0,
    );
    public function __construct($file)
    {
        $this->file = $file;
        $this->getType();
    }
    /*
    内部方法：获取图片类型
    1、多文件
    2、单文件
    3、base64流文件
     */
    private function getType()
    {
        $file = $this->file;
        if (is_array($file)) {
            // 多文件
            if (is_array($file['name'])) {
                $type = 1;
            } else { // 单文件
                $type = 2;
            }
        } else {
            $type = 3;
        }
        $this->type = $type;
    }
    /*
    内部方法：获取图片后缀
    @param path 图片路径
    @return suffix 后缀名 如：jpg
     */
    private function getSuffix($path)
    {
        $file = fopen($path, "rb");
        $bin = fread($file, 2); // 只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
        $suffix = "unknow";
        if ($typeCode == 255216) {
            $suffix = "jpg";
        } elseif ($typeCode == 7173) {
            $suffix = "gif";
        } elseif ($typeCode == 13780) {
            $suffix = "png";
        } elseif ($typeCode == 6677) {
            $suffix = "bmp";
        } elseif ($typeCode == 7798) {
            $suffix = "exe";
        } elseif ($typeCode == 7784) {
            $suffix = "midi";
        } elseif ($typeCode == 8297) {
            $suffix = "rar";
        } elseif ($typeCode == 7368) {
            $suffix = "mp3";
        } elseif ($typeCode == 0) {
            $suffix = "mp4";
        } elseif ($typeCode == 8273) {
            $suffix = "wav";
        }
        return $suffix;
    }
    /*
    内部方法：保存流文件
    @param stream 文件流
    @param suffix 后缀
    @param dir 保存文件夹
    @return path 文件路径
     */
    private function uploadBase64($stream, $suffix, $dir)
    {
        if (empty($stream)) {
            return false;
        }

        if (preg_match('/^(data:(\s)?(image|img)\/(\w+);base64,)/', $stream, $str)) {
            $path = $dir . md5(rand(100000, 999999)) . ".{$suffix}";
            if (file_put_contents($path, base64_decode(str_replace($str[1], '', $stream)))) {
                return $path;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /*
    校验文件类型是否合法
    @return ret true:合法 false:非法
     */
    public function checkType()
    {
        $file = $this->file;
        $type = $this->type;
        $validSuffix = $this->suffix;
        $ret = true;
        if ($type == 1) { // 多文件
            foreach ($file['tmp_name'] as $v) {
                $suffix = $this->getSuffix($v);
                if (!in_array($suffix, $validSuffix)) {
                    $ret = false;
                    break;
                }
            }
        } elseif ($type == 2) { // 单文件
            $suffix = $this->getSuffix($file['tmp_name']);
            if (!in_array($suffix, $validSuffix)) {
                $ret = false;
            }
        } else { // base64文件
            $suffix = $this->getSuffix($file);
            if (!in_array($suffix, $validSuffix)) {
                $ret = false;
            }
        }
        return $ret;
    }
    /*
    校验文件尺寸是否合法
    @return ret true:合法 false:非法
     */
    public function checkMeasure()
    {
        $file = $this->file;
        $type = $this->type;
        $validMeasure = $this->measure;
        $ret = true;
        if ($type == 1) { // 多文件
            foreach ($file['tmp_name'] as $v) {
                $measure = getimagesize($v);
                $width = $measure[0];
                $height = $measure[1];
                if (($width < $validMeasure['width'][0] || $width > $validMeasure['width'][1]) || ($height < $validMeasure['height'][0] || $height > $validMeasure['height'][1])) {
                    $ret = false;
                    break;
                }
            }
        } elseif ($type == 2) { // 单文件
            $measure = getimagesize($file['tmp_name']);
            $width = $measure[0];
            $height = $measure[1];
            if (($width < $validMeasure['width'][0] || $width > $validMeasure['width'][1]) || ($height < $validMeasure['height'][0] || $height > $validMeasure['height'][1])) {
                $ret = false;
            }
        } else { // base64文件
            $measure = getimagesize($file);
            $width = $measure[0];
            $height = $measure[1];
            if (($width < $validMeasure['width'][0] || $width > $validMeasure['width'][1]) || ($height < $validMeasure['height'][0] || $height > $validMeasure['height'][1])) {
                $ret = false;
            }
        }
        return $ret;
    }
    /*
    校验文件大小是否合法
    @return ret true:合法 false:非法
     */
    public function checkSize()
    {
        $file = $this->file;
        $type = $this->type;
        $validSize = $this->size;
        $ret = true;
        if ($type == 1) { // 多文件
            foreach ($file['tmp_name'] as $v) {
                $size = filesize($v);
                if (($size < $validSize['min'] * 1024 * 1024) || ($size > $validSize['max'] * 1024 * 1024)) {
                    $ret = false;
                    break;
                }
            }
        } elseif ($type == 2) { // 单文件
            $size = filesize($file['tmp_name']);
            if (($size < $validSize['min'] * 1024 * 1024) || ($size > $validSize['max'] * 1024 * 1024)) {
                $ret = false;
            }
        } else { // base64文件
            $info = file_get_contents($file);
            $size = strlen($info);
            if (($size < $validSize['min'] * 1024 * 1024) || ($size > $validSize['max'] * 1024 * 1024)) {
                $ret = false;
            }
        }
        return $ret;
    }
    /*
    @desc：获取图片信息
     */
    public function info()
    {
        $file = $this->file;
        $type = $this->type;
        $ret = array();
        if ($type == 1) { // 多文件
            foreach ($file['tmp_name'] as $k => $v) {
                $measure = getimagesize($v);
                $width = $measure[0];
                $height = $measure[1];
                $size = filesize($v) / 1024 / 1024;
                $ret[$k] = array(
                    'width' => $width,
                    'height' => $height,
                    'size' => number_format($size, 2),
                );
            }
        } elseif ($type == 2) { // 单文件
            $measure = getimagesize($file['tmp_name']);
            $width = $measure[0];
            $height = $measure[1];
            $size = filesize($file['tmp_name']) / 1024 / 1024;
            $ret = array(
                'width' => $width,
                'height' => $height,
                'size' => number_format($size, 2),
            );
        } else { // base64文件
            $measure = getimagesize($file);
            $width = $measure[0];
            $height = $measure[1];
            $info = file_get_contents($file);
            $size = strlen($info) / 1024 / 1024;
            $ret = array(
                'width' => $width,
                'height' => $height,
                'size' => number_format($size, 2),
            );
        }
        return $ret;
    }
    /*
    内部方法：缩放图片
     */
    private function scale($path, $suffix, $ratio)
    {
        list($width, $height) = getimagesize($path);
        $new_w = $ratio * $width;
        $new_h = $ratio * $height;
        $new_s = imagecreatetruecolor($new_w, $new_h);
        if (in_array($suffix, array('jpg', 'jpeg'))) {
            $img = imagecreatefromjpeg($path);
        } elseif ($suffix == 'png') {
            $img = imagecreatefrompng($path);
        } elseif ($suffix == 'gif') {
            $img = imagecreatefromgif($path);
        } else {
            return false;
        }
        $ret1 = imagecopyresized($new_s, $img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        if (in_array($suffix, array('jpg', 'jpeg'))) {
            $ret2 = imagejpeg($new_s, $path);
        } elseif ($suffix == 'png') {
            $ret2 = imagepng($new_s, $path);
        } elseif ($suffix == 'gif') {
            $ret2 = imagegif($new_s, $path);
        } else {
            return false;
        }
        imagedestroy($new_s);
        imagedestroy($img);
        if ($ret1 && $ret2) {
            return $path;
        } else {
            return false;
        }
    }
    /*
    内部方法：裁剪图片
     */
    private function crop($path, $suffix, $cut_width, $cut_height)
    {
        $cut_x;
        $cut_y;
        $min;
        $size = getimagesize($path);
        $width = $size[0];
        $height = $size[1];
        $min = min($width, $height);
        $cut_width = ($cut_width > $min) ? $min : $cut_width;
        $cut_height = ($cut_height > $min) ? $min : $cut_height;
        $cut_x = ($width - $cut_width) / 2;
        $cut_y = ($height - $cut_height) / 2;
        if (in_array($suffix, array('jpg', 'jpeg'))) {
            $img = imagecreatefromjpeg($path);
        } elseif ($suffix == 'png') {
            $img = imagecreatefrompng($path);
        } elseif ($suffix == 'gif') {
            $img = imagecreatefromgif($path);
        } else {
            return false;
        }
        $new_s = imagecreatetruecolor($cut_width, $cut_height);
        $ret1 = imagecopyresampled($new_s, $img, 0, 0, $cut_x, $cut_y, $cut_width, $cut_height, $cut_width, $cut_height);
        if (in_array($suffix, array('jpg', 'jpeg'))) {
            $ret2 = imagejpeg($new_s, $path);
        } elseif ($suffix == 'png') {
            $ret2 = imagepng($new_s, $path);
        } elseif ($suffix == 'gif') {
            $ret2 = imagegif($new_s, $path);
        } else {
            return false;
        }
        imagedestroy($new_s);
        imagedestroy($img);
        if ($ret1 && $ret2) {
            return $path;
        } else {
            return false;
        }
    }
    /*
    内部方法：生成缩略图
     */
    private function thumb($path, $suffix, $cut_width, $cut_height)
    {
        $cut_x;
        $cut_y;
        $ratio = 1;
        $size = getimagesize($path);
        $width = $size[0];
        $height = $size[1];
        $cw;
        $ch;
        if ($width / $height >= $cut_width / $cut_height) {
            $ratio = $cut_height / $height;
        } else {
            $ratio = $cut_width / $width;
        }
        $path = $this->scale($path, $suffix, $ratio, $path);
        $width *= $ratio;
        $height *= $ratio;
        $cut_x = abs($cut_width - $width) / 2;
        $cut_y = abs($cut_height - $height) / 2;
        if (in_array($suffix, array('jpg', 'jpeg'))) {
            $img = imagecreatefromjpeg($path);
        } elseif ($suffix == 'png') {
            $img = imagecreatefrompng($path);
        } elseif ($suffix == 'gif') {
            $img = imagecreatefromgif($path);
        } else {
            return false;
        }
        $new_s = imagecreatetruecolor($cut_width, $cut_height);
        $ret1 = imagecopyresampled($new_s, $img, 0, 0, $cut_x, $cut_y, $cut_width, $cut_height, $cut_width, $cut_height);
        if (in_array($suffix, array('jpg', 'jpeg'))) {
            $ret2 = imagejpeg($new_s, $path);
        } elseif ($suffix == 'png') {
            $ret2 = imagepng($new_s, $path);
        } elseif ($suffix == 'gif') {
            $ret2 = imagegif($new_s, $path);
        } else {
            return false;
        }
        imagedestroy($new_s);
        imagedestroy($img);
        if ($ret1 && $ret2) {
            return $path;
        } else {
            return false;
        }
    }
    /*
    保存
    @param dir 存储的文件夹 如：'./'
    @return ret 存储的文件路径 如：'./test.jpg'
     */
    public function save($dir)
    {
        $file = $this->file;
        $type = $this->type;
        $scale = $this->scale;
        $crop = $this->crop;
        $thumb = $this->thumb;
        $is_scale = $scale['is_scale'];
        $is_crop = $crop['is_crop'];
        $is_thumb = $thumb['is_thumb'];
        $ratio = $scale['ratio'];
        $crop_width = $crop['width'];
        $crop_height = $crop['height'];
        $thumb_width = $thumb['width'];
        $thumb_height = $thumb['height'];
        if ($type == 1) { // 多文件
            foreach ($file['tmp_name'] as $k => $v) {
                $suffix = $this->getSuffix($v);
                $name = $dir . md5(rand(100000, 999999)) . '.' . $suffix;
                $flag = file_put_contents($name, file_get_contents($v));
                if (!$flag) {
                    $ret = false;
                } else {
                    if ($is_scale) {
                        $name = $this->scale($name, $suffix, $ratio);
                    }
                    if ($is_crop) {
                        $name = $this->crop($name, $suffix, $crop_width, $crop_height);
                    }
                    if ($is_thumb) {
                        $name = $this->thumb($name, $suffix, $thumb_width, $thumb_height);
                    }
                    $ret[$k] = $name;
                }
            }
        } elseif ($type == 2) { // 单文件
            $suffix = $this->getSuffix($file['tmp_name']);
            $name = $dir . md5(rand(100000, 999999)) . '.' . $suffix;
            $flag = file_put_contents($name, file_get_contents($file['tmp_name']));
            if (!$flag) {
                $ret = false;
            } else {
                if ($is_scale) {
                    $name = $this->scale($name, $suffix, $ratio);
                }
                if ($is_crop) {
                    $name = $this->crop($name, $suffix, $crop_width, $crop_height);
                }
                if ($is_thumb) {
                    $name = $this->thumb($name, $suffix, $thumb_width, $thumb_height);
                }
                $ret = $name;
            }
        } else { // base64文件
            $suffix = $this->getSuffix($file);
            $name = $dir . md5(rand(100000, 999999)) . '.' . $suffix;
            $flag = file_put_contents($name, file_get_contents($file));
            if (!$flag) {
                $ret = false;
            } else {
                if ($is_scale) {
                    $name = $this->scale($name, $suffix, $ratio);
                }
                if ($is_crop) {
                    $name = $this->crop($name, $suffix, $crop_width, $crop_height);
                }
                if ($is_thumb) {
                    $name = $this->thumb($name, $suffix, $thumb_width, $thumb_height);
                }
                $ret = $name;
            }
        }
        return $ret;
    }
}
