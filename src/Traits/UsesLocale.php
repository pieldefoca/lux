<?php

namespace Pieldefoca\Lux\Traits;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Models\Locale;

trait UsesLocale
{
    public $currentLocale;
    public $currentLocaleCode;
    public $defaultLocale;
    public $defaultLocaleCode;
    public $hasLocalizableFields = true;
    public $hasMultipleLocales = false;

    public function mountUsesLocale()
    {
        $this->defaultLocale = Locale::default();
        $this->defaultLocaleCode = $this->defaultLocale->code;

        $this->currentLocale = $this->defaultLocale;
        $this->currentLocaleCode = $this->currentLocale->code;

        $this->hasMultipleLocales = Locale::count() > 1;
    }

    public function disableLocaleSelector()
    {
        $this->hasMultipleLocales = false;
    }

    #[On('select-locale')]
    public function selectLocale($locale)
    {
        $this->currentLocale = Locale::where('code', $locale)->first();
        $this->currentLocaleCode = $locale;
    }
}
