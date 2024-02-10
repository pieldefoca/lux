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

        $page = Page::where("slug->{$currentLocale}", $currentPath)
            ->orWhere("slug->{$defaultLocale}", $currentPath)
            ->first();

        // if(is_null($page)) {
        //     return Page::find('projects.show');
        // }

        return $page;
    }
}
