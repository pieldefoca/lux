<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Console\Command;
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Facades\Pages;
use Spatie\Browsershot\Browsershot;

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
    protected $description = 'Refresh pages data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach(Page::all() as $page) {
            Browsershot::url($page->localizedUrl(config('lux.fallback_locale')))
                ->setNodeBinary('/Users/pieldefoca/Library/Application\ Support/Herd/config/nvm/versions/node/v21.7.1/bin/node')
                ->setNpmBinary('/Users/pieldefoca/Library/Application\ Support/Herd/config/nvm/versions/node/v21.7.1/bin/npm')
                ->windowSize(1920, 1080)
                ->save(public_path('img/screenshots/'.$page->id.'.png'));
        }

        Pages::generatePageRoutes();
    }
}