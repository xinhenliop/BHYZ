<?php

namespace App\Models;

class Route
{
    function __construct()
    {
        $this->Route = include('../config/apiRoute.php');
    }

    public static function getRoute($uri, $string)
    {
        return (new self())->get($uri, $string);
    }

    function get($name, $default)
    {
        return $this->Route[$name] ?? $default;
    }

    function set($name, $value): void
    {
        if (isset($this->Route[$name])) {
            ($this->Route[$name] = $value) && $this->save();
        }
    }

    public function save()
    {
        file_put_contents('../config/apiRoute.php', '<?php return ' . var_export($this->Route, true) . ';');
    }

    function add($name, $value): void
    {
        if (isset($this->Route[$name])) {
            (array_push($this->Route[$name], $value)) && $this->save();
        }
    }

    function count()
    {
        return count($this->Route);
    }
}
