<?php

namespace Pieldefoca\Lux;

use Livewire\Livewire;
use Livewire\Component;
use Illuminate\Support\Str;
use Pieldefoca\Lux\Http\Middleware\LuxMiddleware;
use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Support\Lux;
use Pieldefoca\Lux\Support\Pages;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Pieldefoca\Lux\Support\Translator;
use Illuminate\Support\ServiceProvider;
use Pieldefoca\Lux\Console\Commands\LuxPages;
use Pieldefoca\Lux\Console\Commands\MakePage;
use Pieldefoca\Lux\Console\Commands\LuxInstall;
use Pieldefoca\Lux\Http\Middleware\PageMiddleware;
use Pieldefoca\Lux\Http\Middleware\LocaleMiddleware;
use Pieldefoca\Lux\Support\MediaManager\MediaManager;
use Symfony\Component\Yaml\Yaml;

class LuxServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerFacades();

        $this->registerPageRoutes();

        $this->configureLivewire();

        $this->registerComponents();

        $this->registerDisks();

        $this->resolveMissingRoutes();

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

		$this->loadViewsFrom(__DIR__.'/../resources/views', 'lux');

		$this->loadTranslationsFrom(__DIR__.'/../lang', 'lux');

		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if($this->app->runningInConsole()) {
            $this->commands([
                LuxInstall::class,
                MakePage::class,
                LuxPages::class,
            ]);
        }
    }

    protected function registerFacades()
	{
        $this->app->bind('lux', fn($app) => new Lux());

		$this->app->bind('lux-translator', fn($app) => new Translator());

		$this->app->bind('lux-pages', fn($app) => new Pages());

		$this->app->singleton('lux-media-manager', fn($app) => new MediaManager());
	}

    protected function registerPageRoutes()
    {
        $this->app->call(function() {
    		if(file_exists(base_path('routes/pages.php'))) {
                Route::middleware(['web', LocaleMiddleware::class, PageMiddleware::class])
                    ->group(base_path('routes/pages.php'));
            }
		});
    }

    protected function configureLivewire()
    {
        $shouldUseLuxComponents = str(request()->path())->startsWith(config('lux.prefix'))
            || request()->path() === 'login';

        if($shouldUseLuxComponents) {
            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/admin/livewire/update', $handle)->middleware(['web', LuxMiddleware::class]);
            });

            config([
                'livewire.layout' => 'lux::components.layouts.app',
                'livewire.class_namespace' => 'Pieldefoca\\Lux\\Livewire',
            ]);
        }
    }

    protected function registerComponents()
	{
		Blade::componentNamespace('Pieldefoca\\Lux\\Views\\Components', 'lux');

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

    protected function resolveMissingRoutes()
    {
        app('url')->resolveMissingNamedRoutesUsing(function($name, $parameters, $absolute) {
            $page = Page::where('id', $name)->first();

            if(is_null($page)) {
                throw new \Exception("Page [{$name}] not found");
            }

            $routes = app('router')->getRoutes();

            $locale = app()->currentLocale();

            $route = $routes->getByName("{$page->id}.{$locale}");

            return app('url')->toRoute($route, $parameters, $absolute);
        });
    }

    protected function registerDisks(): void
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
