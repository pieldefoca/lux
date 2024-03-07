<?php

namespace Pieldefoca\Lux\Traits;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Models\Locale;

trait UsesLocale
{
    public $locale;
    public $currentLocale;
    public $currentLocaleCode;
    public $defaultLocale;
    public $defaultLocaleCode;
    public $hasLocalizableFields = true;
    public $hasMultipleLocales = false;

    public function mountUsesLocale()
    {
        $defaultLocale = Locale::default();
        // $this->defaultLocaleCode = $this->defaultLocale->code;
        $this->locale = session('luxLocale', $defaultLocale->code);

        // $this->currentLocale = $this->defaultLocale;
        // $this->currentLocaleCode = $this->currentLocale->code;

        $this->hasMultipleLocales = Locale::count() > 1;
    }

    public function updatedUsesLocale($field, $value)
    {
        if($field === 'locale') {
            session(['luxLocale' => $value]);

            $this->dispatch('locale-changed', locale: $value);
            $this->dispatch('refresh-drag');
        }
    }

    public function disableLocaleSelector()
    {
        $this->hasMultipleLocales = false;
    }

    #[On('select-locale')]
    public function selectLocale($locale)
    {
        // $this->currentLocale = Locale::where('code', $locale)->first();
        // $this->currentLocaleCode = $locale;
        $this->locale = $locale;

        session(['luxLocale' => $locale]);

        $this->dispatch('locale-changed', locale: $locale);

        $this->dispatch('refresh-drag');
    }
}
