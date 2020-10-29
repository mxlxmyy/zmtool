<?php
declare (strict_types = 1);

namespace zmtool;

/**
 * 验证相关
 */
class Va
{
    /**
     * 验证手机号码
     */
    public static function checkMmobile(string $mobile): bool
    {
        $check = '/^(1([3456789][0-9]))\d{8}$/';
        if (preg_match($check, $mobile)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @desc 验证邮箱规则
     * @author 苏鹏
     * @date 2019/9/20
     * @param $mail
     * @return bool
     */
    public static function checkMail(string $mail): bool
    {
        $check = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/';
        if (preg_match($check, $mail)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 账号限制 字母开头 英文、数字、下划线
     * 长度6-24位
     */

    public static function checkAccount(string $value): bool
    {
        $rule = '/^[a-zA-Z]{1}([-_a-zA-Z0-9]{5,25})$/';
        preg_match($rule, $value, $match);
        if (empty($match[0])) {
            return false;
        }
        return true;
    }
}
