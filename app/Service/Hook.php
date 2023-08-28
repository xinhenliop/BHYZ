<?php

namespace App\Service;

interface Hook
{
    public function add_hook($hook_name, $hook = array());

    public function remove_hook($hook_name);

    public function add_url($uri, $class);

    public function remove_url($uri);

}
