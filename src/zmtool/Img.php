<?php
declare (strict_types = 1);

namespace zmtool;

/**
 * 图片相关
 */
class Img
{
    /**
     * 本地图片转base64
     * @param  string $imageUrl 图片地址
     */
    public static function imgToBase64(string $imageUrl): string
    {
        $imageInfo = getimagesize($imageUrl);
        if (!$imageInfo) {
            return return_info(0, 'no file');
        }
        return "data:{$imageInfo['mime']};base64," . chunk_split(base64_encode(file_get_contents($imageUrl)));
    }

    /**
     * base64 字符串转图片
     * @param  string $base64Str base64字符串
     * @param  string $dir        保存文件夹，默认日期字符串(20191030)
     */
    public static function base64ToImg(string $base64Str, string $dir = "", string $path = ""): string
    {
        if (empty($base64Str)) {
            return "";
        }

        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Str, $result);
        $imgStr = base64_decode(str_replace($result[1], '', $base64Str));
        if (empty($imgStr)) {
            return "";
        }

        $filename = DIRECTORY_SEPARATOR . 'uploadpic' . DIRECTORY_SEPARATOR;
        if (empty(trim($dir))) {
            $filename .= date('Ymd') . DIRECTORY_SEPARATOR;
        } else {
            $filename .= $dir . DIRECTORY_SEPARATOR;
        }
        if (empty($path)) {
        	$path = getcwd();
        } else {
        	$path = rtrim($path, DIRECTORY_SEPARATOR);
        }
        $path .= $filename;

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            if (!is_dir($path)) {
                return "";
            }
        } else
        if (!is_writable($path)) {
            chmod($path, 0755);
        }

        $imgName = md5(date('YmdHis') . rand(1000, 9999)) . '.' . $result[2];

        if (false == file_put_contents($path . $imgName, $imgStr)) {
            return "";
        }

        return $filename . $imgName;
    }
}
