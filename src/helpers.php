<?php

use Pieldefoca\Lux\Support\Lux;

if(!function_exists('lux')) {
    function lux(): Lux
    {
        return new Lux();
    }
}

if(!function_exists('clean_trans')) {
    function clean_trans($key): string
    {
        return str(trans($key))->replace('<p>', '')->replace('</p>', '')->toString();
    }
}