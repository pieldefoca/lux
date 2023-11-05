<?php

namespace Pieldefoca\Lux;

use Pieldefoca\Lux\Console\Commands\MakeLux;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Support\Translator;

class LuxServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../config/lux.php', 'lux');
	}

	public function boot()
	{
		$this->registerFacades();

		config([
			'livewire.layout' => 'lux::components.layouts.app',
			'livewire.class_namespace' => 'Pieldefoca\\Lux\\Livewire',
		]);

		$this->app['config']['filesystems.disks.luxPages'] = [
			'driver' => 'local', 
			'root' => public_path('pages'), 
			'url' => env('APP_URL').'/pages', 
			'visibility' => 'public', 
			'throw' => false, 
		];

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

			$this->publishes([
				__DIR__.'/../resources/views/components/sidebar/index.blade.php' => resource_path('views/components/lux/sidebar/index.blade.php')
			], 'lux-components');

			$this->commands([
				MakeLux::class,
			]);
		}
		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'lux');
		$this->loadTranslationsFrom(__DIR__.'/../lang', 'lux');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		View::share('luxLocales', Locale::all());

		ComponentAttributeBag::macro('localizedWireModel', function($locale) {
			foreach($this->whereStartsWith('wire:model') as $attribute => $value) {
				return "{$attribute}={$value}.{$locale}";
			}
		});
	}

	protected function registerFacades()
	{
		$this->app->bind('lux-translator', fn($app) => new Translator());
	}
}
