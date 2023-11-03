<?php

namespace Pieldefoca\Lux\Livewire\Translations;

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Index extends LuxComponent
{
    public Locale $editingLocale;
    public $langFiles = [];
    public $selectedFile = '';
    public $translations = [];
    
    public function mount()
    {
        $defaultLocale = Locale::default();
        $nonDefaultLocales = Locale::where('default', false)->get();
        $this->editingLocale = $nonDefaultLocales->first();

        $langPath = lang_path();
        $localeFiles = File::files(lang_path('/' . $defaultLocale->code));
        foreach($localeFiles as $localeFile) {
            $filename = basename($localeFile);
            $humanName = str(pathinfo($filename)['filename'])->title()->toString();
            $this->langFiles[$humanName] = $filename;
        }

        $this->selectedFile = 'app.php';
        $this->translations = include lang_path("{$defaultLocale->code}/{$this->selectedFile}");
    }

    public function updatedSelectedFile()
    {
        $this->translations = include lang_path("{$this->defaultLocaleCode}/{$this->selectedFile}");
    }

    #[Computed]
    public function editingLocaleCode()
    {
        return $this->editingLocale->code;
    }

    public function save()
    {
        asort($this->translations);
        $finalTranslations = [];
        $string = "<?php\n\nreturn [\n";
        foreach($this->translations as $oldKey => $translation) {
            $newKey = str($translation)->words(6)->slug()->toString();
            $translation = addslashes($translation);
            $string .= "\t\"{$newKey}\" => \"{$translation}\",\n";
            $finalTranslations[$newKey] = $translation;

            $fileKey = pathinfo($this->selectedFile)['filename'];
            $view = file_get_contents(resource_path('views/welcome.blade.php'));
            $view = Str::replace("trans('{$fileKey}.{$oldKey}')", "trans('{$fileKey}.{$newKey}')", $view);
            $view = Str::replace("trans(\"{$fileKey}.{$oldKey}\")", "trans(\"{$fileKey}.{$newKey}\")", $view);
            $view = Str::replace("@lang('{$fileKey}.{$oldKey}')", "@lang('{$fileKey}.{$newKey}')", $view);
            $view = Str::replace("@lang(\"{$fileKey}.{$oldKey}\")", "@lang(\"{$fileKey}.{$newKey}\")", $view);
            file_put_contents(resource_path('views/welcome.blade.php'), $view);
        }
        $string .= "\n];";
        file_put_contents(lang_path('es/' . $this->selectedFile), $string);
    }

    public function render()
    {
        return view('lux::livewire.translations.index');
    }
}
