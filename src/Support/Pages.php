<?php

namespace Pieldefoca\Lux\Support;

use Illuminate\Support\Str;
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Locale;

class Pages
{
    public function generatePageRoutes()
    {
        $content = <<<TXT
        <?php

        use Illuminate\Support\Facades\Route;

        /*
         * BE CAREFUL
         *
         * This file is automatically generated,
         * you should not make any changes to it
         *
         */


        TXT;

        foreach(Page::published()->get() as $page) {
            foreach(Locale::active()->get() as $locale) {
                if($page->isDynamic()) {
                    $path = trim($page->translate('slug_prefix', $locale->code)) . '/{model}';
                    $path = $locale->default ? $path : "{$locale->code}/{$path}";
                } else {
                    $path = trim($page->translate('slug', $locale->code));
                    $path = $locale->default ? $path : "{$locale->code}/{$path}";
                }

                $action = $page->livewireComponentClass();

                $content .= "Route::get('/{$path}', {$action})->name('{$page->view}.{$locale->code}');\n";
            }
            $content .= "\n";
        }

        file_put_contents(base_path('routes/pages.php'), $content);
    }

}