<?php

namespace App\Libs;

class Waf
{
    private string $waf;

    public function __construct($waf = "args")
    {
        $this->waf = json_decode(file_get_contents("../app/waf/" . $waf . ".json"), true);
    }

    public function wafAction($input)
    {


    }

    public function wafQuery($input)
    {

    }

    public function wafArgs($input)
    {

    }

    public function wafUrl($input)
    {

    }

}
