<?php

use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Support\Lux;
use Pieldefoca\Lux\Models\Media;
use Pieldefoca\Lux\Models\Locale;

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

        return route("{$page->view}.{$locale}", $attributes);
   }
}

if(!function_exists('filesize_for_humans')) {
    function filesize_for_humans($size, $precision = 2) {
        $units = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision).$units[$i];
    }
}