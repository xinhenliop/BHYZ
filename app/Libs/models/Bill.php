<?php

namespace App\Libs\models;


use Exception;

class Bill
{
    /**
     * bill_count
     * bill statistics count
     * @param $array array
     * @return float
     */
    public static function bill_count(array $array): float
    {
        $balance = 0;
        try {
            $array = \App\Models\Bill::select("price")->get();
            if ($array == null || count($array) == 0) return 0;
            foreach ($array as $value) {
                $balance += $value->balance;
            }
        } catch (Exception $e) {
        }
        return $balance;
    }
}
