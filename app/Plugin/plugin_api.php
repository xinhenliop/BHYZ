<?php

namespace App\Plugin;

class plugin_api
{
    public $api_list = [];

    public function __construct()
    {
        $this->api_list = [];
    }

    public function init($uri)
    {
        return $this->api_list[$uri][0]->$this->api_list[$uri][1];
    }
}
