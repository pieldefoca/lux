<?php

namespace Pieldefoca\Lux;

use Livewire\Livewire;
use Livewire\Component;
use Illuminate\Support\Str;
use Pieldefoca\Lux\Support\Lux;
use Pieldefoca\Lux\Models\Media;
use Pieldefoca\Lux\Support\Pages;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Pieldefoca\Lux\Support\Translator;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Pieldefoca\Lux\Livewire\MediaSelector;
use Pieldefoca\Lux\Observers\MediaObserver;
use Pieldefoca\Lux\Console\Commands\LuxUser;
use Pieldefoca\Lux\Console\Commands\MakeLux;
use Pieldefoca\Lux\Console\Commands\LuxPages;
use Pieldefoca\Lux\Console\Commands\LuxInstall;
use Pieldefoca\Lux\Console\Commands\LuxUploads;
use Pieldefoca\Lux\Console\Commands\LuxProcessMedia;
use Pieldefoca\Lux\Support\MediaManager\MediaManager;

class LuxServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../config/lux.php', 'lux');
	}

	public function boot()
	{
		Media::observe(MediaObserver::class);

		$this->configureLivewire();

		$this->registerFacades();

		$this->registerComponents();

		Livewire::component('lux-media-selector', MediaSelector::class);

		$this->registerDisks();

		if($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__.'/../config/lux.php' => config_path('lux.php'),
				__DIR__.'/../config/fortify.php' => config_path('fortify.php'),
			], 'lux-config');

            $this->publishes([
                __DIR__.'/../database/seeders' => database_path('seeders')
            ], 'lux-seeders');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/lux'),
            ], 'lux-assets');

			$this->commands([
				LuxInstall::class,
				MakeLux::class,
				LuxUser::class,
				LuxPages::class,
				LuxUploads::class,
				LuxProcessMedia::class,
			]);
		}
		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'lux');
		$this->loadTranslationsFrom(__DIR__.'/../lang', 'lux');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		ComponentAttributeBag::macro('localizedWireModel', function($locale) {
			foreach($this->whereStartsWith('wire:model') as $attribute => $value) {
				if(! Str::contains($attribute, '.blur')) {
					$attribute .= '.blur';
				}
				return "{$attribute}={$value}.{$locale}";
			}
		});
	}

	protected function configureLivewire()
	{
		$path = request()->path();
		if(str($path)->startsWith('admin')) {
			Livewire::setUpdateRoute(function ($handle) {
				return Route::post('/admin/livewire/update', $handle)->middleware(['web']);
			});

			if(str($path)->startsWith('admin')) {
				config([
					'livewire.layout' => 'lux::components.layouts.app',
					'livewire.class_namespace' => 'Pieldefoca\\Lux\\Livewire',
				]);
			}
		}
	}

	protected function registerComponents()
	{
		$luxAppPath = app_path('Livewire/Lux');

		$files = File::allFiles($luxAppPath);
		
		$namespace = 'App\Livewire\Lux\\';
		foreach($files as $file) {
			if($file->getExtension() !== 'php') continue;

			$component = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($file->getRealPath(), realpath(app_path('Livewire/Lux')).DIRECTORY_SEPARATOR)
            );

			$componentName = str($component)->after('Livewire\\');
			$splits = array_map(fn($s) => Str::snake($s, '-'), explode('\\', $componentName));
			$componentName = implode('.', $splits);
			
			if (is_subclass_of($component, Component::class)) {
                Livewire::component($componentName, $component);
            }
		}
	}

	protected function registerFacades()
	{
		$this->app->bind('lux', fn($app) => new Lux());

		$this->app->bind('lux-translator', fn($app) => new Translator());

		$this->app->bind('lux-pages', fn($app) => new Pages());

		$this->app->bind('lux-media-manager', fn($app) => new MediaManager());
	}

	protected function registerDisks()
	{
		$this->app['config']['filesystems.disks.avatars'] = [
			'driver' => 'local', 
			'root' => public_path('avatars'), 
			'url' => env('APP_URL').'/avatars', 
			'visibility' => 'public', 
			'throw' => false, 
		];
	}
}
