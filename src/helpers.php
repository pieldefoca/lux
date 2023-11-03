<?php

use Pieldefoca\Lux\Models\Page;
use Illuminate\Support\Facades\Route;

if(!function_exists('lux_route')) {
    function lux_route($routeName) {
        $page = Page::where('view', $routeName)->first();

        return $page->localizedRoute();
    }
}