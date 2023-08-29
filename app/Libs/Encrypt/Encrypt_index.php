<?php

namespace App\Libs\Encrypt;

class Encrypt_index
{
    public static function socket_encrypt($model)
    {
        switch ($model) {
            case 1:
                return "HEX";
            case 2:
                return "Base64";
            case 0:
            default:
                return "不编码";

        }
    }

    public static function encrypt($str, $key = null, $mode = 0)
    {
        return 0;
    }

    public static function transmission(mixed $transmission)
    {
        switch ($transmission) {
            case 1:
                return "GET加密";
            case 2:
                return "POST加密";
            case 3:
                return "自动识别";
            case 0:
            default:
                return "明文传输";

        }
    }

    public static function Encry_model(): array
    {
        $Enpath = "../app/Libs/Encrypt/";
        $path = array_filter(array_slice(scandir($Enpath), 2), function ($file) use ($Enpath) {
            return is_file($Enpath . $file) && strpos($file, '.php') > 0 && !strpos($file, '_index.php') && !strpos($file, 'O.php');
        });
        $EnMode = [];
        foreach ($path as $keys => $value) {
            $EnMode[] = substr($value, 0, strpos($value, ".php"));
        }

        return $EnMode;
    }

    public static function charToHex($char)
    {
    }

    public static function hexToChar($char)
    {
    }
}
