<?php

namespace Pieldefoca\Lux;

use Illuminate\Support\ServiceProvider;

class LuxServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../config/lux.php', 'lux');
	}

	public function boot()
	{
		config([
			'livewire.layout' => 'lux::components.layouts.app',
			'livewire.class_namespace' => 'Pieldefoca\\Lux\\Livewire',
		]);

		if($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__.'/../config/lux.php' => config_path('lux.php'),
			], 'lux-config');

            $this->publishes([
                __DIR__.'/../database/seeders' => database_path('seeders')
            ], 'lux-seeders');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/lux'),
            ], 'lux-assets');
		}
		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'lux');
		$this->loadTranslationsFrom(__DIR__.'/../lang', 'lux');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
	}
}
