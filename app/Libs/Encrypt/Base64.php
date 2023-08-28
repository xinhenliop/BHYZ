<?php

namespace App\Libs\Encrypt;

class Base64
{
    static function encrypt($str, $key = null)
    {
        return base64_encode($str);
    }

    static function decrypt($str, $key = null)
    {
        $encoded = str_replace(' ', '+', $str);
        $decoded = "";
        for ($i = 0; $i < ceil(strlen($encoded) / 256); $i++)
            $decoded = $decoded . base64_decode(substr($encoded, $i * 256, 256));
        return $decoded;
    }
}
