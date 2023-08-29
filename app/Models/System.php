<?php

namespace App\Models;

class System
{
    function __construct()
    {
        $this->config = include('../config/system.php');
    }

    static function getSystem($name, $value, $default)
    {
        return (new System())->getConfig($name, $value, $default);
    }

    function getConfig($name, $value, $default)
    {
        return $this->config[$name][$value] ?? $default;
    }

    static function setSystem($name, $value): void
    {
        (new System())->setConfig($name, $value);
    }

    function setConfig($name, $value)
    {
        if (isset($this->config[$name])) {
            ($this->config[$name] = $value) && file_put_contents('../config/system.php', '<?php return ' . var_export($this->config, true) . ';');
        }
    }
}
