<?php

namespace Pieldefoca\Lux\Livewire\Translations;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\File;
use Pieldefoca\Lux\Facades\Translator;
use Illuminate\Support\Facades\Storage;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    public $locales = [];
    public $editingLocaleCode;
    public $langFiles = [];
    public $previousSelectedFile;
    public $selectedFile = '';
    public $originalDefaultTranslations = [];
    public $defaultTranslations = [];
    public $originalEditingTranslations = [];
    public $editingTranslations = [];
    public $search = '';

    public $currentChanges = 0;
    
    public function mount()
    {
        $defaultLocale = Locale::default();
        $nonDefaultLocales = Locale::where('default', false)->get();
        $this->editingLocaleCode = $nonDefaultLocales->first()->code;
        $this->locales = $nonDefaultLocales->pluck('code')->toArray();

        $localeFiles = File::files(lang_path('/' . $defaultLocale->code));
        foreach($localeFiles as $index => $localeFile) {
            $filename = basename($localeFile);
            $humanName = str(pathinfo($filename)['filename'])->title()->toString();
            $this->langFiles[$humanName] = $filename;
            if($index === 0) $this->selectedFile = $filename;
        }

        $this->setTranslations($defaultLocale->code);
    }

    public function updatingSelectedFile()
    {
        if($this->currentChanges > 0) {
            $this->previousSelectedFile = $this->selectedFile;

            return $this->notifyError('🧐 Parece que tienes cambios sin guardar. Revísalos y guarda antes de cambiar de fichero.');
        }

    }

    public function updatedSelectedFile()
    {
        if($this->currentChanges > 0) {
            $this->selectedFile = $this->previousSelectedFile;

            $this->previousSelectedFile = null;
        } else {
            $this->setTranslations();
        }
    }

    public function updated($field, $value) {
        if(str($field)->contains('defaultTranslations') || str($field)->contains('editingTranslations')) {
            $this->currentChanges = 0;
            $this->currentChanges += count(array_diff($this->originalDefaultTranslations, $this->defaultTranslations));
            $this->currentChanges += count(array_diff($this->originalEditingTranslations, $this->editingTranslations));
        }
    }

    public function setTranslations($defaultLocale = null)
    {
        if(is_null($defaultLocale)) $defaultLocale = $this->defaultLocale->code;

        $this->defaultTranslations = include lang_path("{$defaultLocale}/{$this->selectedFile}");
        if(is_array($this->defaultTranslations)) { ksort($this->defaultTranslations); }
        $this->originalDefaultTranslations = $this->defaultTranslations;

        if(File::exists(lang_path("{$this->editingLocaleCode}/{$this->selectedFile}"))) {
            $this->editingTranslations = include lang_path("{$this->editingLocaleCode}/{$this->selectedFile}");
            ksort($this->editingTranslations);
        } else {
            $this->editingTranslations = $this->defaultTranslations;
        }
        $this->originalEditingTranslations = $this->editingTranslations;
    }

    #[Computed]
    public function defaultLocale()
    {
        return Locale::default();
    }

    #[Computed]
    public function editingLocale()
    {
        return Locale::where('code', $this->editingLocaleCode)->first();
    }

    #[Computed]
    public function selectedFileKey()
    {
        return pathinfo($this->selectedFile)['filename'];
    }

    #[Computed]
    public function filteredDefaultTranslations()
    {
        return $this->filterTranslations($this->defaultTranslations);
    }

    #[Computed]
    public function filteredEditingTranslations()
    {
        return $this->filterTranslations($this->editingTranslations);
    }

    protected function filterTranslations($translations)
    {
        return collect($translations)->when($this->search, function($collection, $search) {
            return $collection->filter(function($value, $key) use($search) {
                $value = str($value)->ascii()->lower();
                $key = str($key)->ascii()->lower();
                $search = Str::ascii($search);
                return $key->contains($search) 
                    || $value->contains($search)
                    || str($this->selectedFileKey.'.'.$key)->contains($search);
            });
        })->toArray();
    }

    public function selectLocale($locale)
    {
        $this->editingLocaleCode = $locale;

        $this->setTranslations();
    }

    #[On('save-translations')]
    public function save()
    {
        $this->saveDefaultTranslations();

        $this->saveLocaleTranslations();

        $this->currentChanges = 0;

        $this->notifySuccess('🤙🏾 Has actualizado las traducciones correctamente');
    }

    protected function saveDefaultTranslations()
    {
        asort($this->defaultTranslations);

        Translator::saveTranslations($this->defaultTranslations, lang_path($this->defaultLocale->code . '/' . $this->selectedFile));
    }

    protected function saveLocaleTranslations()
    {
        $langFolder = lang_path($this->editingLocaleCode);

        if(!file_exists($langFolder)) mkdir($langFolder);

        Translator::saveTranslations($this->editingTranslations, lang_path($this->editingLocaleCode . '/' . $this->selectedFile));
    }

    public function render()
    {
        return view('lux::livewire.translations.index');
    }
}
