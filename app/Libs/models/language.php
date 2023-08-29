<?php

namespace App\Libs\models;

use Illuminate\Support\Facades\App;

class language
{
    function __construct()
    {
        App::setLocale(config("system")['system']['language']);
    }

    static function localeList()
    {
        return [
            ""
        ];
    }
}
