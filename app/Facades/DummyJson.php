<?php

namespace App\Facades;

class DummyJson
{
    public static function __callStatic($method, $args)
    {
        return app()->make(static::getFacadeAccessor())->$method();
    }
    protected static function getFacadeAccessor()
    {
        return 'DummyJson';
    }
}