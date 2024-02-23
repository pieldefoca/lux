<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Pieldefoca\Lux\Models\Page;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Facades\Pages;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\DB;
use Pieldefoca\Lux\Models\Mediable;
use Illuminate\Support\Facades\File;
use Pieldefoca\Lux\Facades\Translator;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Traits\HasMediaFields;
use Pieldefoca\Lux\Livewire\Attributes\MediaGallery;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;

class Edit extends LuxComponent
{
    use HasMediaFields;

    public Page $page;

    public $name;

    #[Translatable]
    public $slug;

    public $target;

    public $controller;

    public $controller_action;

    public $livewire_component;

    #[Translatable]
    public $title;

    #[Translatable]
    public $description;

    public $visible;

    public $translations = [];

    #[Translatable]
    public $legalPageContent = [];

    public $search = '';

    public int $swappingMediaId;
    public string $swappingMediaKey;

    public function mount()
    {
        $this->name = $this->page->name;
        $this->slug = $this->page->getTranslations('slug');
        $this->target = is_null($this->page->controller) ? 'livewire' : 'controller';
        $this->controller = $this->page->controller;
        $this->controller_action = $this->page->controller_action;
        $this->livewire_component = $this->page->livewire_component;
        $this->title = $this->page->getTranslations('title');
        $this->description = $this->page->getTranslations('description');
        $this->is_home_page = $this->page->is_home_page;
        $this->visible = $this->page->visible;

        $this->getTranslations();

        $this->initMediaFields($this->page);
    }

    public function updatedSlug($value)
    {
        if($this->locale === $this->defaultLocaleCode) {
            foreach(Locale::all() as $locale) {
                if(array_key_exists($locale->code, $this->slug) && empty($this->slug[$locale->code])) {
                    $this->slug[$locale->code] = $value;
                }
            }
        }
    }

    #[On('locale-changed')]
    public function onLocaleChanged()
    {
        $this->search = '';
    }

    #[Computed]
    public function filteredTranslations()
    {
        $filteredTranslations = [];

        foreach($this->translations as $locale => $translations) {
            $filteredTranslations[$locale] = $this->filteredLocaleTranslations($locale);
        }

        return $filteredTranslations;
    }

    protected function filteredLocaleTranslations($locale)
    {
        return collect($this->translations[$locale])->when($this->search, function($collection, $search) {
            return $collection->filter(function($value, $key) use($search) {
                $value = str($value)->ascii()->lower();
                $key = str($key)->ascii()->lower();
                $search = Str::ascii($search);
                return $key->contains($search)
                    || $value->contains($search);
            });
        })->toArray();
    }

    #[Computed]
    public function images()
    {
        return $this->page->getImages($this->locale);
    }

    #[Computed]
    public function videos()
    {
        return $this->page->getVideos($this->locale);
    }

    #[Computed]
    public function files()
    {
        return $this->page->getFiles($this->locale);
    }

    #[Computed]
    public function commonMedia()
    {
        $query = Mediable::with('media')
            ->where('lux_mediable_type', 'BladeComponent');

        if($this->locale === Locale::default()->code) {
            $query->where(function($query) {
                return $query->where('locale', $this->locale)
                    ->orWhere('locale', null);
            });
        } else {
            $query->where('locale', $this->locale);
        }

        return $query->get();
    }

    public function getTranslations()
    {
        $filename = $this->page->langFilePath;

        foreach(Locale::all() as $locale) {
            if(!$this->page->hasLangFileFor($locale->code)) {
                $this->page->createLangFile($locale->code);
            }
            $this->translations[$locale->code] = include(lang_path("{$locale->code}/{$filename}"));

            if($this->page->isLegalPage()) {
                $this->legalPageContent[$locale->code] = $this->translations[$locale->code]['content'] ?? '';
            }
        }
    }

    public function swapMedia($id, $key)
    {
        $this->swappingMediaId = $id;
        $this->swappingMediaKey = $key;

        $this->dispatch('swap-media', selectedId: $id);
    }

    #[On('media-swapped')]
    public function mediaSwapped(int $mediaId)
    {
        $query = DB::table('lux_mediables')
            ->where('lux_media_id', $this->swappingMediaId)
            ->where(function($query) {
                $query->where('locale', $this->locale)
                    ->orWhere('locale', null);
            })
            ->where('key', $this->swappingMediaKey);

        if(str($this->swappingMediaKey)->startsWith('component')) {
            $query->where('lux_mediable_type', 'BladeComponent');
        } else {
            $query->where('lux_mediable_type', 'Pieldefoca\Lux\Models\Page')
                ->where('lux_mediable_id', $this->page->id);
        }

        $query->update(['lux_media_id' => $mediaId]);
    }

    #[On('save-page')]
    public function save()
    {
        $validated = $this->validate();

        $validated = $this->cleanSlug($validated);

        $validated = $this->cleanController($validated);

        $validated = $this->cleanLivewireComponent($validated);

        $this->page->update($validated);

        foreach($this->translations as $locale => $localeTranslations) {
            if($this->page->isLegalPage()) {
                $localeTranslations['content'] = $this->legalPageContent[$locale];
            }
            Translator::saveTranslations($localeTranslations, lang_path($locale . '/' . $this->page->langFilePath));
        }

        Pages::generatePageRoutes();

        $this->notifySuccess('🤙🏾 Has editado la página correctamente');
    }

    protected function cleanSlug($validated)
    {
        foreach($this->slug as $locale => $slug) {
            if(empty($slug)) {
                $validated['slug'][$locale] = '/';
            }
        }

        return $validated;
    }

    protected function cleanController($validated)
    {
        if($this->target !== 'controller') {
            $validated['controller'] = null;
            $validated['controller_action'] = null;
            return $validated;
        }

        if(!Str::startsWith($validated['controller'], 'App\Http\Controllers')) {
            $validated['controller'] = "App\Http\Controllers\\{$validated['controller']}";
            return $validated;
        }

        return $validated;
    }

    protected function cleanLivewireComponent($validated)
    {
        if($this->target !== 'livewire') {
            $validated['livewire_component'] = null;
            return $validated;
        }

        $validated['livewire_component'] = Pages::getLivewireComponentClassName($validated['livewire_component']);

        return $validated;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'slug' => ['required'],
            'target' => ['required', Rule::in(['controller', 'livewire']), 'exclude'],
            'controller' => ['nullable', Rule::requiredIf($this->target === 'controller')],
            'controller_action' => ['nullable', Rule::requiredIf($this->target === 'controller')],
            'livewire_component' => ['nullable', Rule::requiredIf($this->target === 'livewire')],
            'title' => ['nullable'],
            'description' => ['nullable'],
            'visible' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Escribe un nombre descriptivo para la página',
            'slug.required' => 'Escribe una url',
            'target.required' => 'Elige un destino',
            'target.in' => 'Elige un destino válido',
            'controller.required' => 'Escribe el nombre del controlador',
            'controller_action.required' => 'Escribe la acción',
            'livewire_component.required' => 'Escribe el nombre del componente',
        ];
    }

    public function render()
    {
        return view('lux::livewire.pages.edit')
            ->layout('lux::components.layouts.app', [
                'title' => trans('lux::lux.edit-page-title'),
                'subtitle' => trans('lux::lux.edit-page-subtitle', ['name' => $this->page->name]),
            ]);
    }
}
