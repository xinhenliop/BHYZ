<?php

namespace App\Http\Controllers\Json;

class response
{
    static function message(int $code, string $msg, $data = null)
    {
        return response()->json(["code" => $code, "msg" => $msg, "data" => $data]);
    }

}
