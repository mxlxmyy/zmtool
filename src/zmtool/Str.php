<?php

declare (strict_types = 1);

namespace zmtool;

/**
 * 字符串相关
 */
class Str
{
    /**
     * URL安全的 base64编码
     * '+' -> '-'
     * '/' -> '_'
     * '=' -> ''
     * @param unknown $string
     */
    public static function base64En(string $str): string
    {
        $data = base64_encode($str);
        return str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    }

    /**
     * URL安全的 base64解码
     * '-' -> '+'
     * '_' -> '/'
     * 字符串长度%4的余数，补'='
     * @param unknown $string
     */
    public static function base64De(string $str): string
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $str);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}
