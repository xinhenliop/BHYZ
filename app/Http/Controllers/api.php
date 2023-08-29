<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\app;
use App\Http\Controllers\Api\card;
use App\Http\Controllers\Api\log;
use App\Models\Route;
use App\Plugin\plugin_api;
use Illuminate\Http\Request;

class api
{
    public static function response($response, $out_format = 1): string
    {
        if ($out_format == 1) {
            return json_encode($response);
        } else if ($out_format == 2) {
            return self::xml($response);
        } else if ($out_format == 0) {
            return self::text($response);
        }
        return ($response);
    }

    public static function xml($array)
    {
        $response = "<?xml version=\"1.0\" encoding=\"utf-8\">";
        $response .= "<root>";
        $response .= "<code>" . $array['code'] . "</code>";
        $response .= "<msg>" . $array['msg'] . "</msg>";
        $response .= "<data>";
        foreach ($array['data'] as $key => $value) {
            $response .= "<$key>$value</$key>";
        }
        $response .= "</data>";
        $response .= "</root>";
        return $response;
    }

    private static function text($array)
    {
        $response = $array['code'] . "|" . $array['msg'];
        foreach ($array['data'] as $key => $value) {
            $response .= "|$value";
        }
        return $response;
    }

    public function hashUri($uri)
    {
        return strtoupper(substr(md5($uri), 0, 6));
    }

    public function init(Request $request, $uri): string
    {
        $plugin = new plugin_api();
        //if(array_key_exists($uri,$plugin->api_list)) return $plugin->init($uri,$request);
        if (($uris = Route::getRoute($uri, false))) return (new $uris['class']($uris['appid'], $request))->$uris['method']();
        if (method_exists(($class = new app(null, $request)), $uri)) return $class->$uri();
        if (method_exists(($class = new card(null, $request)), $uri)) return $class->$uri();
        if (method_exists(($class = new log(null, $request)), $uri)) return $class->$uri();
        return response()->json(["code" => 200, "message" => "Invalid api！"]);
    }

    function __invoke()
    {
        return view('Api');
    }
}
