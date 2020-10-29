<?php
declare (strict_types = 1);

namespace zmtool;

/**
 * 数组相关
 */
class Arr
{
    /**
     * 排序二维数组
     * @param  $array 要排序的数组
     * @param  $key 根据哪个数组键的值排序
     * @param  $sort 正序false  倒序true
     */
    public static function orderArray(array $array, string $key, bool $sort = true): array
    {
        $key_names = array_column($array, $key);
        if ($sort) {
            array_multisort($key_names, SORT_DESC, $array);
        } else {
            array_multisort($key_names, SORT_ASC, $array);
        }
        return $array;
    }

    /**
     * xml转数组
     * @param data xml字符串
     * @return arr 解析出的数组
     */
    public static function xmlToArray(string $data): array
    {
        $obj  = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($obj);
        return json_decode($json, true);
    }
}
