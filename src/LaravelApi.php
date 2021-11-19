<?php

namespace Codeby\LaravelApi;

use Illuminate\Support\Facades\Facade;

class LaravelApi extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'laravel-api';
    }
}
