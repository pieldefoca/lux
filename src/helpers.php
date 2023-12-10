<?php

use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Support\Lux;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

if(!function_exists('lux')) {
    function lux() {
        return new Lux();
    }
}

if(!function_exists('page')) {
    function page($page, $attributes = [], $locale = null) {
        $page = Page::where('key', $page)->first();

        if(is_null($page)) abort(404);

        if(is_null($locale)) $locale = app()->currentLocale();

        return route("{$page->view}.{$locale}");
   }
}

if(!function_exists('page_media')) {
    function page_media($key) {
        $media = Media::where('custom_properties->key', $key)->first();

        return $media?->getUrl();
    }
}