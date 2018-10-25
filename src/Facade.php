<?php

namespace Snower\LaravelForsun;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class Facade extends LaravelFacade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'forsun';
    }
}