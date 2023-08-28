<?php

namespace App\Models;

class Plugin
{
    private mixed $Plugin;

    function __construct()
    {
        $this->Plugin = include('../config/plugin.php');
    }

    function get($name, $default)
    {
        return $this->Plugin[$name] ?? $default;
    }

    function set($name, $value): void
    {
        if (isset($this->Plugin[$name])) {
            ($this->Plugin[$name] = $value) && $this->save();
        }
    }

    public function save()
    {
        file_put_contents('../config/plugin.php', '<?php return ' . var_export($this->Plugin, true) . ';');
    }

    function add($name, $value): void
    {
        if (isset($this->Plugin[$name])) {
            (array_push($this->Plugin[$name], $value)) && $this->save();
        }
    }

    function count()
    {
        return count($this->Plugin);
    }

}
