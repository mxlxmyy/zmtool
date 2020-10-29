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

    /**
     * 隐藏部分邮箱地址
     * @param  $email
     * @return string
     */
    public static function hiddenSomeMail(string $email): string
    {
        if ($email == '') {
            return $email;
        }

        $arr   = explode('@', $email);
        $head  = substr($arr[0], 0, 1);
        $str_h = str_replace($arr[0], $head . '***', $arr[0]);
        return $str_h . '@' . $arr[1];
    }

    /**
     * 隐藏部分手机号
     * @param  $phone
     * @return string
     */
    public static function hiddenSomePhone(string $phone): string
    {
        if ($phone == '') {
            return $phone;
        }

        $count = strlen($phone);
        return cut_str($phone, 3, 0) . '****' . cut_str($phone, 4, $count - 4);
    }

    /**
     * 产生随机字串，可用来自动生成密码
     * 默认长度6位 字母和数字混合 支持中文
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
     * @param string $addChars 额外字符
     */
    public static function randString(int $len = 6, int $type = null, string $addChars = ''): string
    {
        $str = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            default:
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZ23456789abcdefghijkmnpqrstuvwxyz' . $addChars;
                break;
        }
        if ($len > 10) {
            //位数过长重复字符串一定次数
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str   = substr($chars, 0, $len);
        } else {
            // 中文随机字
            for ($i = 0; $i < $len; $i++) {
                $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
     * 截取日期字符串
     * @param $date 格式：“YYYY-MM-DD HH:ii:ss”
     * @param $m 设置要返回的格式：y(年)、m(月)、d(日)、h(小时)、i(分钟)、s(秒)
     */
    public static function reDateStr(string $date, string $m = 'y-m'): string
    {
        if (empty($date)) {
            return '';
        }

        $date    = explode(' ', $date);
        $date[0] = explode('-', $date[0]);
        $date[1] = explode(':', $date[1]);
        $date    = array_merge($date[0], $date[1]);

        return preg_replace(['/y/', '/m/', '/d/', '/h/', '/i/', '/s/'], $date, $m);
    }

    /**
     * 返回时间字符串
     * 为0或null则返回空
     * @param $time 时间戳或日期
     * @param $m 日期格式
     */
    public static function reDate($time = false, string $m = 'Y-m-d'): string
    {
        if ($time === false) {
            $time = time();
        } else
        if ($time === null || $time === 0 || $time === '0' || $time === '') {
            return '';
        } else
        if (is_string($time) && strval(intval($time)) !== $time) {
            return re_date_str($time, strtolower($m));
        }

        return date($m, $time);
    }

    /**
     * 补全数字字符串
     * @param $number 数字
     * @param $length 补全后长度
     */
    public static function reNumberLen($number, int $length = 4): string
    {
        $number = strval($number);
        for ($i = strlen($number); $i < $length; $i++) {
            $number = '0' . $number;
        }

        return $number;
    }
}
