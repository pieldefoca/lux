<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('lux.prefix'))
	->middleware(['web', 'auth'])
	->group(function() {
		Route::get('/', Pieldefoca\Lux\Livewire\Dashboard::class)->name('lux.dashboard');

		Route::get('/contacto', Pieldefoca\Lux\Livewire\Contact\Form::class)->name('lux.contact.form');
	});
