<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::prefix(config('lux.prefix'))
	->middleware(['web', 'auth'])
	->group(function() {
		Route::get('/', Pieldefoca\Lux\Livewire\Dashboard::class)->name('lux.dashboard');

        Route::get('/sliders', Pieldefoca\Lux\Livewire\Sliders\Index::class)->name('lux.sliders.index');
        Route::get('/sliders/{slider}/editar', Pieldefoca\Lux\Livewire\Sliders\Edit::class)->name('lux.sliders.edit');

		// Route::get('/blog/categorias', Pieldefoca\Lux\Livewire\Blog\Categories\Index::class)->name('lux.blog.categories.index');

		// Route::get('/blog/etiquetas')->name('lux.blog.tags.index');

		// Route::get('/blog/posts', Pieldefoca\Lux\Livewire\Blog\Posts\Index::class)->name('lux.blog.posts.index');
		// Route::get('/blog/posts/nuevo', Pieldefoca\Lux\Livewire\Blog\Posts\Create::class)->name('lux.blog.posts.create');
		// Route::get('/blog/posts/{post}/editar', Pieldefoca\Lux\Livewire\Blog\Posts\Edit::class)->name('lux.blog.posts.edit');

		Route::get('/mi-perfil', Pieldefoca\Lux\Livewire\Profile::class)->name('lux.profile');

		Route::get('/paginas', Pieldefoca\Lux\Livewire\Pages\Index::class)->name('lux.pages.index');
		Route::get('/paginas/{page}/editar', Pieldefoca\Lux\Livewire\Pages\Edit::class)->name('lux.pages.edit');
		
		Route::get('/traducciones', Pieldefoca\Lux\Livewire\Translations\Index::class)->name('lux.translations.index');

		Route::get('/media', Pieldefoca\Lux\Livewire\MediaManager\Index::class)->name('lux.media.index');

		Route::get('/usuarios', Pieldefoca\Lux\Livewire\Users\Index::class)->name('lux.users.index');
		Route::get('/roles', Pieldefoca\Lux\Livewire\Roles\Index::class)->name('lux.roles.index');
		Route::get('/permisos', Pieldefoca\Lux\Livewire\Permissions\Index::class)->name('lux.permissions.index');

		Route::post('/tinymce/upload', function() {
			$file = request()->file();
	
			$filename = request()->file('file')->store('/', 'tinymceUploads');
	
			return response()->json([
				'location' => Storage::disk('tinymceUploads')->url($filename),
			]);
		});
	});
