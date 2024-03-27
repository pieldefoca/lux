<?php

namespace Pieldefoca\Lux\Livewire\Traits;

trait UsesLocale
{
    public $locale;

    public $fallbackLocale;

    public function setupLocale()
    {
        $this->fallbackLocale = config('lux.fallback_locale');

        $this->locale = session('luxLocale', $this->fallbackLocale);
    }
}