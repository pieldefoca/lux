<?php

namespace Pieldefoca\Lux\Support;

use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Locale;

class Lux
{
    public function currentPage()
    {
        $currentPath = request()->path();

        $currentLocale = app()->currentLocale();
        $defaultLocale = Locale::default()->code;

        if($currentPath === '/') {
            return Page::where('is_home_page', true)->first();
        } 

        if(str($currentPath)->startsWith("{$currentLocale}/") || $currentPath === $currentLocale) {
            if($currentPath === $currentLocale) {
                $currentPath = '';
            } else {
                $currentPath = str($currentPath)->replace("{$currentLocale}/", '')->toString();
            }
        }

        $page = Page::where("slug->{$currentLocale}", $currentPath)
            ->orWhere("slug->{$defaultLocale}", $currentPath)
            ->first();

        if(is_null($page)) {
            $pathSplits = explode('/', $currentPath);
            array_pop($pathSplits);
            $pathPrefix = implode('/', $pathSplits);
    
            $page = Page::where("slug_prefix->{$currentLocale}", $pathPrefix)
                ->orWhere("slug->{$defaultLocale}", $pathPrefix)
                ->first();
        }

        return $page;
    }
}
