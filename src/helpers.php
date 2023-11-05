<?php

use Pieldefoca\Lux\Models\Page;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

if(!function_exists('lux_route')) {
    function lux_route($routeName) {
        $page = Page::where('view', $routeName)->first();

        return $page->localizedRoute();
    }
}

if(!function_exists('page_media')) {
    function page_media($key) {
        $media = Media::where('custom_properties->key', $key)->first();

        return $media?->getUrl();
    }
}