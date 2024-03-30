<?php

namespace Pieldefoca\Lux\Facades;

use Illuminate\Support\Facades\Facade;

class Lux extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lux';
    }
}