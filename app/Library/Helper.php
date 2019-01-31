<?php

if (!function_exists('config')) {
    /**
     * 加载配置文件并返回配置文件内容
     * 可以使用 . 符号获取配置文件中的key，支持递归获取子key
     * @param $param
     * @return mixed
     */
    function config($param)
    {
        $explode = explode('.', $param);
        $fileName = array_shift($explode);
        $file = ROOT_PATH . '/config/' . $fileName . '.php';
        $globalKey = md5('config');
        $globalSubKey = hash_hmac('md5', $fileName, $fileName);
        if (isset($GLOBALS[$globalKey][$globalSubKey])) {
            return empty($explode) ?
                $GLOBALS[$globalKey][$globalSubKey]
                :
                get_deep_value($GLOBALS[$globalKey][$globalSubKey], implode('.', $explode));
        } else if (file_exists($file)) {
            $config = include_once $file;
            if ($config === true) {
                return false;
            }
            $GLOBALS[$globalKey][$globalSubKey] = $config;
            return empty($explode) ?
                $config
                :
                get_deep_value($config, implode('.', $explode));
        }
        return false;
    }
}

if (!function_exists('get_deep_value')) {
    /**
     * 递归获取一个数组中指定key的值
     * @param        $array
     * @param        $keys
     * @param string $delimiter
     * @return mixed
     */
    function get_deep_value($array, $keys, $delimiter = '.')
    {
        $keys = explode($delimiter, $keys);
        $key = array_shift($keys);
        if (sizeof($keys) > 0 && isset($array[$key])) {
            return get_deep_value($array[$key], implode($delimiter, $keys), $delimiter);
        } else {
            return $array[$key] ?? null;
        }
    }
}