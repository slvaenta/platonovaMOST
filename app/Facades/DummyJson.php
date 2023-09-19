<?php

namespace App\Facades;

class DummyJson
{
    protected static function getFacadeAccessor($name)
    {
        return app()[$name];
    }
    public static function __callStatic($method, $args)
    {
        return (self::getFacadeAccessor('dummyJson'))
        ->$method(...$args);
    }
}