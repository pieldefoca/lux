<?php

namespace Pieldefoca\Lux\Facades;

use Illuminate\Support\Facades\Facade;

class Pages extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lux-pages';
    }
}