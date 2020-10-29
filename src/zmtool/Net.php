<?php
declare (strict_types = 1);

namespace zmtool;

/**
 * 网络请求相关
 */
class Net
{
    /**
     * curl请求
     * @param String $sUrl     请求地址
     * @param Array  $header  header数据 ["Authorization: token"]
     * @param Array  $postData    POST数据
     * @return String
     */
    public static function curl(string $url, array $header = [], array $postData = []): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180); //从服务器接收缓冲完成时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); //连接服务器前等待时间
        if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        $res = curl_exec($ch);
        if ($err = curl_error($ch)) {
            curl_close($ch);
            return $err;
        }
        curl_close($ch);
        return $res;
    }

    /**
     * curl请求 设置代理ip地址
     * @param String $sUrl     请求地址
     * @param Array  $header  header数据 ["Authorization: token"]
     * @param Array  $postData    POST数据
     * @return String
     */
    public static function curlAgent(array $ip, string $url, array $header = [], array $postData = [], int $timeout = 180): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
        curl_setopt($ch, CURLOPT_PROXY, $ip[0]); //代理服务器地址
        curl_setopt($ch, CURLOPT_PROXYPORT, $ip[1]); //代理服务器端口
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //https请求不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url); //设置链接
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0");
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //设置超时
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);

        if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        //设置跳转location最多3次
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

        $res = curl_exec($ch);

        if ($err = curl_error($ch)) {
            curl_close($ch);
            return $err;
        }
        curl_close($ch);
        return $res;
    }

    /**
     * 转化链接中的特殊字符
     * @param  $url 链接地址
     * @param  $noNeed 不需要转化的字符
     */
    public static function urlFilter(string $url, array $noNeed = []): string
    {
        if (!in_array('/', $noNeed)) {
            $noNeed[] = '/';
        }
        $replace = [];
        for ($i = 0; $i < count($noNeed); $i++) {
            $replace[] = '_._._' . $i;
        }

        $url = urlencode(str_replace($noNeed, $replace, $url));

        $replace[] = "+";
        $noNeed[]  = "%20";
        return str_replace($replace, $noNeed, $url);
    }
}
