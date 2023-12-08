<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Pieldefoca\Lux\Models\Page;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\File;
use Pieldefoca\Lux\Facades\Pages;
use Pieldefoca\Lux\Facades\Translator;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Attributes\MediaGallery;
use Pieldefoca\Lux\Livewire\Attributes\Translatable;
use Pieldefoca\Lux\Traits\HasMediaFields;

class Edit extends LuxComponent
{
    use HasMediaFields;

    public Page $page;

    #[Rule('required', message: 'Escribe un nombre')]
    public $name;

    #[Translatable]
    public $slug;
    #[Translatable]
    public $slug_prefix;

    #[Rule('required', message: 'Elige una vista para esta página')]
    public $view;

    #[Translatable]
    public $title;

    #[Translatable]
    public $description;

    #[Rule('nullable|boolean', message: 'Elige un valor válido')]
    public $is_home_page;

    #[Rule('nullable|boolean', message: 'Elige un valor válido')]
    public $visible;

    public $currentHomeMessage;

    public $translations = [];

    public int $swappingMediaId;

    public function mount()
    {
        $this->name = $this->page->name;
        $this->slug = $this->page->getTranslations('slug');
        $this->slug_prefix = $this->page->getTranslations('slug_prefix');
        $this->view = $this->page->view;
        $this->title = $this->page->getTranslations('title');
        $this->description = $this->page->getTranslations('description');
        $this->is_home_page = $this->page->is_home_page;
        $this->visible = $this->page->visible;

        $this->getTranslations();

        $this->initMediaFields($this->page);
    }

    public function updatedIsHomePage($value)
    {
        if($value) {
            $currentHome = Page::where('is_home_page', true)->first();

            if($currentHome && $currentHome->id !== $this->page->id) {
                $this->currentHomeMessage = 'La página "'.$currentHome->name.'" es la página de inicio actual.<br> Al guardar estos ajustes se sustituirá la página de inicio anterior por la actual.';
            }
        } else {
            $this->currentHomeMessage = '';
        }
    }

    public function updatedSlug($value)
    {
        if($this->currentLocaleCode === $this->defaultLocaleCode) {
            foreach(Locale::all() as $locale) {
                if(array_key_exists($locale->code, $this->slug) && empty($this->slug[$locale->code])) {
                    $this->slug[$locale->code] = $value;
                }
            }
        }
    }

    #[Computed]
    public function views()
    {
        $views = [];

        foreach(File::allFiles(resource_path('views/pages')) as $file) {
            if(!str($file->getFilename())->endsWith('.blade.php')) continue;

            $relativePath = $file->getRelativePath();

            if(str($relativePath)->startsWith('livewire/lux')) continue;

            $filename = str($file->getFilename())->replace('.blade.php', '')->toString();

            $views[] = empty($relativePath) ? $filename : "{$relativePath}/$filename";
        }

        return $views;
    }

    #[Computed]
    public function mediaItems()
    {
        return $this->page->getMediaTranslations('media');
    }

    public function getTranslations()
    {
        $filename = $this->page->langFilePath;

        foreach(Locale::all() as $locale) {
            if(!$this->page->hasLangFileFor($locale->code)) {
                $this->page->createLangFile($locale->code);
            }
            $this->translations[$locale->code] = include(lang_path("{$locale->code}/{$filename}"));
        }
    }

    public function swapMedia($id)
    {
        $this->swappingMediaId = $id;

        $this->dispatch('swap-media', selectedId: $id);
    }

    #[On('media-swapped')]
    public function mediaSwapped(int $mediaId)
    {
        $this->page->media()
            ->where('locale', $this->currentLocaleCode)
            ->where('lux_media_id', $this->swappingMediaId)
            ->first()
            ->pivot
            ->update(['lux_media_id' => $mediaId]);
    }

    #[On('save-page')]
    public function save()
    {
        // dd($this->media);
        // $this->page->clearMediaCollection('media');
        // $this->page->addMedia(public_path('img/fire.svg'))
        //     ->preservingOriginal()
        //     ->withCustomProperties([
        //         'key' => 'home.home-101',
        //     ])
        //     ->toMediaCollection('media');
        // return;
        $validated = $this->validate();

        if($this->is_home_page) {
            foreach($validated['slug'] as $locale => $slug) {
                if(empty($slug)) $validated['slug'][$locale] = ' ';
            }
        }

        if($this->page->isDynamic()) {
            $validated['slug'] = null;
        }

        $this->page->update($validated);

        if($this->is_home_page) {
            Page::where('id', '!=', $this->page->id)
                ->where('is_home_page', true)
                ->first()
                ?->update(['is_home_page' => false]);
        }

        foreach($this->translations as $locale => $localeTranslations) {
            Translator::saveTranslations($localeTranslations, lang_path($locale . '/' . $this->page->langFilePath));
        }

        Pages::generatePageRoutes();

        $this->notifySuccess('🤙🏾 Has editado la página correctamente');
    }

    public function rules()
    {
        $slugRequired = !$this->page->isDynamic() && !$this->is_home_page;
        $slugPrefixRequired = $this->page->isDynamic();
        return [
            'name' => ['required'],
            'slug' => [$slugRequired ? 'required' : 'nullable'],
            'slug_prefix' => [$slugPrefixRequired ? 'required' : 'nullable'],
        ];
    }

    public function messages()
    {
        return [

        ];
    }

    public function render()
    {
        return view('lux::livewire.pages.edit');
    }
}
