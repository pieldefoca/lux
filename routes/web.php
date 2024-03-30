<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Pieldefoca\Lux\Http\Middleware\LuxMiddleware;

Route::middleware(['web', LuxMiddleware::class])->group(function () {
	Route::get('/login', Pieldefoca\Lux\Livewire\Auth\Login::class)->name('login');

    Route::post('/logout', function() {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect(route('home'));
    });
});

Route::prefix(config('lux.prefix'))
	->middleware(['web', 'auth', LuxMiddleware::class])
	->group(function() {
		Route::get('/', Pieldefoca\Lux\Livewire\Dashboard::class)->name('lux.dashboard');

		Route::get('/paginas', Pieldefoca\Lux\Livewire\Pages\Index::class)->name('lux.pages.index');
		Route::get('/paginas/{page}/editar', Pieldefoca\Lux\Livewire\Pages\Edit::class)->name('lux.pages.edit');

        Route::get('/sliders', Pieldefoca\Lux\Livewire\Sliders\Index::class)->name('lux.sliders.index');
        Route::get('/sliders/{slider}/editar', Pieldefoca\Lux\Livewire\Sliders\Edit::class)->name('lux.sliders.edit');

		Route::get('/media', Pieldefoca\Lux\Livewire\MediaManager\Index::class)->name('lux.media.index');

		Route::get('/seo', Pieldefoca\Lux\Livewire\Seo\Index::class)->name('lux.seo.index');

		Route::get('/perfil', Pieldefoca\Lux\Livewire\Profile::class)->name('lux.profile');
    });
