<?php

namespace Pieldefoca\Lux\Support;

class Lux
{
    public function currentLocale(): string
    {
        return session('luxLocale', config('lux.fallback_locale'));
    }

    public function fallbackLocale(): string
    {
        return config('lux.fallback_locale');
    }

    public function locales(): array
    {
        return config('lux.locales');
    }
}
