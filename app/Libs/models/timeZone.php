<?php

namespace App\Libs\models;

class timeZone
{
    function __construct()
    {
        date_default_timezone_set(config("system")['system']['time_zone']);
    }

    static function timeZoneList()
    {
        return [
            ""
        ];
    }
}
