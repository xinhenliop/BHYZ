<?php

namespace App\Libs;

class Utils
{
    public static function array_pop_key($array1, $key2)
    {
        $a = array();
        foreach ($array1 as $key => $value) {
            if ($key != $key2) {
                $a[$key] = $value;
            }
        }
        unset($array1);
        return $a;
    }

    public static function getDateFormat()
    {
        $date = "Y-m-d H:i:s";
        return date($date);
    }

    public static function Http_Post($url, $da)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $da);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //绕过ssl验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public static function Http_Get($url)
    {
        //初始化
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //绕过ssl验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    public static function randomString($length, $string = "ABCDEFGHIJKLMNOPQRSTUVWSYZqwertyuioplkjhgfdaszxcvbnm0123456789")
    {
        $lengths = strlen($string) - 1;
        $retstr = "";
        for ($i = 0; $i < $length; $i++) {
            $retstr .= substr($string, rand(0, $lengths), 1);
        }
        return $retstr;
    }
}
