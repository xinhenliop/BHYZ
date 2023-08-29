<?php

namespace App\Libs\Encrypt;

class HEX
{
    static function encrypt($str, $key = null)
    {
        return self::strToHex($str);
    }

    private static function strToHex($string)
    {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++) $hex .= dechex(ord($string[$i]));
        $hex = strtoupper($hex);
        return $hex;
    }

    static function decrypt($str, $key = null)
    {
        return self::hexToStr($str);
    }

    private static function hexToStr($hex)
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $string;
    }


}
/*
$sf = new HEX();
echo $sf->decrypt("83F5B791421368F6FBA0182E6E7E67871AE83C618C985212BF367B8A14F0E987A7D4CB446A36B9A86F3CDB636D9F7CDEB544AF97DBCC5974A473CF044532ADBEBB6FEC1D011");*/
