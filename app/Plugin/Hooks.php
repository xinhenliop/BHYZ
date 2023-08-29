<?php

namespace App\Plugin;

use App\Service\Hook;

class Hooks implements Hook
{
    public static function PluginMenu()
    {
        return [];
    }

    public function add_hook($hook_name, $hook = array())
    {
    }

    public function remove_hook($hook_name)
    {

    }

    public function add_url($uri, $class)
    {

    }

    public function remove_url($uri)
    {

    }
}
