<?php

namespace Pieldefoca\Lux\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Pieldefoca\Lux\Support\MediaManager\MediaManager;

class MediaManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(MediaManager::class, function(Application $app) {
            return new MediaManager();
        });
    }
}
