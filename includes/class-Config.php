<?php

namespace ACF_Gutenberg\Classes;

class Config
{
    public static $myvariablearray = [];

    public static function createDynamic($variable, $value)
    {
        self::$myvariablearray[$variable] = $value;
    }

    public static function __callstatic($name, $arguments)
    {
        if (isset(self::$myvariablearray[$name])){
            return self::$myvariablearray[$name];
        }
    }
}
