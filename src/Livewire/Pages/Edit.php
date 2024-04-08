<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Pieldefoca\Lux\Models\Page;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Pieldefoca\Lux\Models\Mediable;
use Pieldefoca\Lux\Facades\Pages;
use Spatie\Browsershot\Browsershot;
use Pieldefoca\Lux\Facades\Translator;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Traits\LuxForm;

class Edit extends LuxComponent
{
    use LuxForm;

    public $model = Page::class;

    public Page $page;

    public $name;

    public $slug;

    public $target;

    public $controller;

    public $controller_action;

    public $livewire_component;

    public $seo_title;

    public $seo_description;

    public $visible = true;

    public $translations;

    public $legalPageContent;

    public $translationSearch;

    public $swappingMediaId;

    public $swappingMediaKey;

    public function mount(): void
    {
        $this->name = $this->page->name;
        $this->slug = $this->page->translate('slug', $this->locale);
        $this->target = is_null($this->page->controller) ? 'livewire' : 'controller';
        $this->controller = $this->page->controller;
        $this->controller_action = $this->page->controller_action;
        $this->livewire_component = $this->page->livewire_component;
        $this->visible = $this->page->visible;
        $this->seo_title = $this->page->translate('seo_title', $this->locale);
        $this->seo_description = $this->page->translate('seo_description', $this->locale);
        $this->getTranslations();
    }

    #[Computed]
    public function filteredTranslations()
    {
        return collect($this->translations)->when($this->translationSearch, function($collection, $search) {
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
        $images = [];

        $pageImages = $this->page->getImages($this->locale);

        foreach($pageImages as $media) {
            $images[] = [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'name' => $media->fullName,
                'size' => Number::fileSize($media->size),
                'key' => $media->pivot->key,
            ];
        }

        $commonImages = $this->commonMedia->filter(fn($mediable) => $mediable->media->isImage());

        foreach($commonImages as $mediable) {
            $images[] = [
                'id' => $mediable->media->id,
                'url' => $mediable->media->getUrl(),
                'name' => $mediable->media->fullName,
                'size' => Number::fileSize($mediable->media->size),
                'key' => $mediable->key,
            ];
        }

        return $images;
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

        if($this->locale === config('lux.fallback_locale')) {
            $query->where(function($query) {
                return $query->where('locale', $this->locale)
                    ->orWhere('locale', null);
            });
        } else {
            $query->where('locale', $this->locale);
        }

        return $query->get();
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

        $validated = $this->prepareTranslatableAttributes($validated);

        $this->page->update($validated);

        Pages::generatePageRoutes();

        if($this->page->isLegalPage()) {
            $this->translations['content'] = $this->legalPageContent;
        }
        Translator::saveTranslations($this->translations, lang_path($this->locale . '/' . $this->page->langFilePath));

        $this->js("\$store.lux.setDirtyState(false)");

        $this->notifySuccess('🤙 Has editado la página correctamente');
    }

    public function getTranslations()
    {
        $filename = $this->page->langFilePath;

        if(!$this->page->hasLangFileFor($this->locale)) {
            $this->page->createLangFile($this->locale);
        }

        $this->translations = include(lang_path("{$this->locale}/{$filename}"));

        if($this->page->isLegalPage()) {
            $this->legalPageContent = $this->translations['content'] ?? '';
        }
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'slug' => ['required'],
            'target' => ['required', 'exclude'],
            'controller' => ['nullable', Rule::requiredIf($this->target === 'controller')],
            'controller_action' => ['nullable', Rule::requiredIf($this->target === 'controller')],
            'livewire_component' => ['nullable', Rule::requiredIf($this->target === 'livewire')],
            'seo_title' => ['nullable'],
            'seo_description' => ['nullable'],
            'visible' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Escribe un nombre',
            'slug.required' => 'Escribe una url',
            'target.required' => 'Selecciona un destino',
            'target.required' => 'Elige un destino',
            'target.in' => 'Elige un destino válido',
            'controller.required' => 'Escribe el nombre del controlador',
            'controller_action.required' => 'Escribe la acción',
            'livewire_component.required' => 'Escribe el nombre del componente',
        ];
    }

    public function render()
    {
        return view('lux::livewire.pages.edit');
    }
}
