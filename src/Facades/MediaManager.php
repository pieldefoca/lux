<?php

namespace Pieldefoca\Lux\Facades;

use Illuminate\Support\Facades\Facade;

class MediaManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lux-media-manager';
    }
}