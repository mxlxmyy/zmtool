<?php
declare (strict_types = 1);

namespace zmtool;

/**
 * 微信相关
 */
class Wx
{
    /**
     * 判断是否是在微信浏览器里
     */
    public static function isWeixinBrowser(): bool
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($agent, "MicroMessenger")) {
            return false;
        }
        return true;
    }

    /**
     * 获取微信版本号
     */
    public static function getWeixinVersion(): string
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        //这个字符串出现的位置
        $pos = strpos($agent, 'MicroMessenger/');
        //分割字符串
        $a = substr($agent, $pos);
        //再用标记分割字符串
        $b       = strtok($a, ' ');
        $version = trim(str_replace('MicroMessenger/', '', $b));
        $n       = strrpos($version, '.');
        return substr($version, 0, $n);
    }

    /**
     * 判断是否为手机端
     */
    public static function isMobile(): bool
    {
        //使用thinkPHP默认的验证方式
        if (request()->isMobile()) {
            return true;
        }
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 // 找不到为flase,否则为true
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
            return true;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }

        return false;
    }
}
