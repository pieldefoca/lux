<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('lux.prefix'))
	->middleware(['web', 'auth'])
	->group(function() {
		Route::get('/', Pieldefoca\Lux\Livewire\Dashboard::class)->name('lux.dashboard');

        Route::get('/sliders', Pieldefoca\Lux\Livewire\Sliders\Index::class)->name('lux.sliders.index');
        Route::get('/sliders/{slider}/editar', Pieldefoca\Lux\Livewire\Sliders\Edit::class)->name('lux.sliders.edit');

		Route::get('/blog/categorias', Pieldefoca\Lux\Livewire\Blog\Categories\Index::class)->name('lux.blog.categories.index');

		// Route::get('/blog/etiquetas')->name('lux.blog.tags.index');

		Route::get('/blog/posts', Pieldefoca\Lux\Livewire\Blog\Posts\Index::class)->name('lux.blog.posts.index');
		// Route::get('/blog/posts/nuevo')->name('lux.blog.posts.create');
		// Route::get('/blog/posts/{post}/editar')->name('lux.blog.posts.edit');

		Route::get('/contacto', Pieldefoca\Lux\Livewire\Contact\Form::class)->name('lux.contact.form');
	});
