<?php

use Pieldefoca\Lux\Models\Page;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Pieldefoca\Lux\Http\Middleware\PageMiddleware;

try {
	foreach(Page::all() as $page) {
		$slugs = $page->getTranslations('slug');
	
		if(empty($slugs) && $page->is_home_page) {
			Route::view('', $page->view)->middleware(PageMiddleware::class)->name('home');
			foreach(Locale::all() as $locale) {
				Route::view("/{$locale->code}", $page->view)->name('home');
			}
		}
	
		foreach($slugs as $locale => $slug) {
			$path = Locale::default()->code === $locale ? $slug : "{$locale}/{$slug}";
			Route::view($path, $page->view)->middleware(PageMiddleware::class)->name($page->view);
		}
	}
} catch(\Exception $e) {
	logger()->info('Pages table does not exist!');
}

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

		Route::get('/mi-perfil', Pieldefoca\Lux\Livewire\Profile::class)->name('lux.profile');

		Route::get('/paginas', Pieldefoca\Lux\Livewire\Pages\Index::class)->name('lux.pages.index');
		Route::get('/paginas/{page}/editar', Pieldefoca\Lux\Livewire\Pages\Edit::class)->name('lux.pages.edit');
		
		Route::get('/traducciones', Pieldefoca\Lux\Livewire\Translations\Index::class)->name('lux.translations.index');
	});
