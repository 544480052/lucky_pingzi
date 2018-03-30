<?php
// +----------------------------------------------------------------------
// | Created by activity.
// +----------------------------------------------------------------------
// | 公共函数
// +----------------------------------------------------------------------
// | Author: alexander <gt199899@gmail.com>
// +----------------------------------------------------------------------
// | Datetime: 2017-04-20 12:04
// +----------------------------------------------------------------------
// | Perfect Is Shit
// +----------------------------------------------------------------------

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
        $file = ROOT . '/config/' . $fileName . '.php';
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

if (!function_exists('out')) {
    /**
     * 返回配置文件CODE对应的信息组装成json返回
     * @param       $code
     * @param array $data
     * @return bool|string
     */
    function out($code, $data = [])
    {
        $codes = config('code');
        if (isset($codes[$code])) {
            return json_encode(['code' => $code, 'msg' => $codes[$code], 'data' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            return false;
        }
    }
}

if (!function_exists('server_instance')) {
    /**
     * 获取WS server
     * @param bool $server
     * @return \Swoole\WebSocket\Server
     */
    function server_instance($server = false)
    {
        static $_server = false;
        $_server = !empty($server) && $server instanceof \swoole\websocket\server ? $server : $_server;
        return $_server;
    }
}


if (!function_exists('array_orderby')) {
    function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = [];
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}




if (!function_exists('get_property')) {
    /**
     * todo change
     * @param      $obj
     * @param      $property
     * @param null $default
     * @return mixed
     */
    function get_property($obj, $property, $default = null)
    {
        if (!$obj) return $default;
        is_array($obj) && $obj = (object)$obj;
        is_string($obj) && $obj = json_decode($obj);
        if (is_object($obj)) {
            if (is_string($property)) {
                return property_exists($obj, $property) ? $obj->$property : $default;
            } else if (is_array($property)) {
                switch (count($property)) {
                    case 0:
                        return $default;
                        break;
                    case 1:
                        $property = current($property);
                        return property_exists($obj, $property) ? $obj->$property : $default;
                        break;
                    default :
                        $data = [];
                        foreach ($property as $proper) {
                            $data[] = property_exists($obj, $proper) ? $obj->$proper : $default;
                        }
                        return array_combine($property, $data);
                }
            }
        }
        return $default;

    }
}


//取得随机代码
if (!function_exists('rand_keys')) {
    function rand_keys($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return strtoupper($str);
    }
}

if (!function_exists('base64_deal_encode')) {
    function base64_deal_encode($str)
    {
        $src = ["/", "+", "="];
        $dist = ["_x", "_y", "_z"];
        $new = str_replace($src, $dist, $str);
        return $new;
    }
}

if (!function_exists('base64_deal_decode')) {
    function base64_deal_decode($str)
    {
        $src = ["_x", "_y", "_z"];
        $dist = ["/", "+", "="];
        $new = str_replace($src, $dist, $str);
        return $new;
    }
}

if (!function_exists('format_time')) {
    function format_time($time)
    {
        $result = "";
        if (!empty($time)) {


            $x = abs(time() - strtotime($time));
            $str = date("H:i", strtotime($time));


            $ap = date("A", strtotime($time));
            if ($ap == "AM") {
                $str1 = "上午 " . $str;
            } else {
                $str1 = "下午 " . $str;
            }


            if (date("H", strtotime($time)) == '00') {
                $str1 = "凌晨:" . date("i", strtotime($time));;
            }


            $w = date("w", strtotime($time));
            //覆盖当前周
            $wDay = [
                '0' => '星期日',
                '1' => '星期一',
                '2' => '星期二',
                '3' => '星期三',
                '4' => '星期四',
                '5' => '星期五',
                '6' => '星期六',
            ];

            $str2 = $wDay[$w] . " " . $str1;

            $result = date("Y/m/d", strtotime($time)) . " " . $str1;

            if ($x >= abs(2 * 86400 - $x) && $x < abs(6 * 86400 - $x)) {
                $result = $str2;
            }

            if ($x <= abs(2 * 86400 - $x) && $x > abs(86400 - $x)) {
                $result = strstr($str1, '凌晨') ? $str1 : "昨天 " . $str1;
            }

            if ($x <= 86400 - $x && $x > 3600) {
                $result = $str1;
            }

            if ($x < 3600) {
                $result = $str;
            }

        }
        return $result;

    }

}

if (!function_exists('friendly_date')) {
    /**
     * 友好的时间显示
     * @param int    $sTime 待显示的时间
     * @param string $type  类型. normal | mohu | full | ymd | other
     * @return string
     */
    function friendly_date($sTime, $type = 'normal')
    {
        if (!$sTime)
            return '';
        if (!is_numeric($sTime))
            $sTime = strtotime($sTime);
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay = intval(date("z", $cTime)) - intval(date("z", $sTime));
        //$dDay     =   intval($dTime/3600/24);
        $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if ($type == 'normal') {
            if ($dTime < 60) {
                return '刚刚';
            } else if ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
                //今天的数据.年份相同.日期相同.
            } else if ($dYear == 0 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } else if ($dYear == 0) {
                return date("m月d日 H:i", $sTime);
            }
            return date("Y-m-d H:i", $sTime);

        } else if ($type == 'hour') {
            if ($dYear == 0 && $dDay == 0) {
                //当天的显示 H:i
                return date('H:i', $sTime);
            } else {
                return date("y/m/d", $sTime);
            }
        } else if ($type == 'mohu') {
            //模糊时间
            if ($dTime < 60) {
                return $dTime . "秒前";
            } else if ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } else if ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } else if ($dDay > 0 && $dDay <= 7) {
                return intval($dDay) . "天前";
            } else if ($dDay > 7 && $dDay <= 30) {
                return intval($dDay / 7) . '周前';
            } else {
                return intval($dDay / 30) . '个月前';
            }

        } else if ($type == 'full') {
            //full: Y-m-d , H:i:s
            return date("Y-m-d , H:i", $sTime);

        } else if ($type == 'ymd') {
            return date("Y-m-d", $sTime);

        } else {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } else if ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } else if ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } else if ($dYear == 0) {
                return date("Y-m-d H:i", $sTime);
            } else {
                return date("Y-m-d H:i", $sTime);
            }
        }
    }
}

if (!function_exists('asyn_log_instance')) {
    /**
     * 获取异步日志对象
     * @param string $channel
     * @return \Lib\Log\AsynLog|null
     */
    function asyn_log_instance($channel = '')
    {
        $level = env('ENV') == 'PROD' ? 'INFO' : 'DEBUG';
        $logConfig = config('log');
        $log = \Lib\Log\AsynLog::getInstance($channel);
        $log->setLogPath($logConfig['path'])
            ->setLogName($logConfig['name'])
            ->setLevel($level);
        return $log;
    }
}


if (!function_exists('sub_str')) {
    /**
     * 截取UTF-8编码下字符串的函数
     *
     * @param   string $str    被截取的字符串
     * @param   int    $length 截取的长度
     * @param   bool   $append 是否附加省略号
     *
     * @return  string
     */
    function sub_str($str, $length = 0, $append = true)
    {
        $str = str_replace("\n", '', str_replace("\r", '', trim($str)));
        $str = strip_tags($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }
        return $newstr;
    }
}

