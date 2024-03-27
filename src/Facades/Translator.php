<?php

namespace Pieldefoca\Lux\Facades;

use Illuminate\Support\Facades\Facade;

class Translator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lux-translator';
    }
}