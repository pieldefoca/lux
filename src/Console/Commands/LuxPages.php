<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\File;
use Pieldefoca\Lux\Facades\Pages;

class LuxPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan the pages folders and sync it with the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allFiles = File::allFiles(resource_path('views/pages'));

        $locales = Locale::all();

        foreach($allFiles as $file) {
            $relativePathname = $file->getRelativePathname();

            if(str($relativePathname)->endsWith('.blade.php')) {
                $relativePathWithoutExtension = str($relativePathname)->replace('/', '.')->replace('.blade.php', '')->toString();
                $page = Page::where('key', $relativePathWithoutExtension)->first();
                if(is_null($page)) {
                    $name = str($file->getFilename())->replace('.blade.php', '')->replace('-', ' ')->toString();
                    $slug = [];

                    foreach($locales as $locale) {
                        $slug[$locale->code] = ($name === 'home') ? '' : Str::slug($name);
                    }

                    $view = str($relativePathWithoutExtension)->replace('/', '.')->toString();
                    $page = Page::create([
                        'key' => $view,
                        'name' => str($name)->title()->toString(),
                        'slug' => $slug,
                        'view' => $view,
                        'is_home_page' => $name === 'home',
                    ]);

                    foreach($locales as $locale) {
                        $page->createLangFile($locale->code);
                    }
                }
            }
        }

        Pages::generatePageRoutes();
    }
}
