<?php

namespace Gutenberg_Blocks\Classes;

class Config
{
    public static $myvariablearray = [];

    public static function createDynamic($variable, $value)
    {
        self::$myvariablearray[$variable] = $value;
    }

    public static function __callstatic($name, $arguments)
    {
        return self::$myvariablearray[$name];
    }
}
