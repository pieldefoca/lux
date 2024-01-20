<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use ReflectionClass;
use ReflectionProperty;
use Pieldefoca\Lux\Models\Locale;
use Livewire\Attribute as LivewireAttribute;

#[\Attribute]
class Translatable extends LivewireAttribute
{
    public function update($field, $newValue)
    {
        $splits = explode('.', $field);
        $updatedLocale = end($splits);
        $defaultLocale = Locale::default()->code;
        if($updatedLocale !== $defaultLocale) return;

        $currentValue = $this->getValue();
        $defaultLocaleValue = $currentValue[$defaultLocale];
        $newCurrentValue = $currentValue;

        foreach($currentValue as $locale => $value) {
            if($locale === $defaultLocale) continue;

            if(empty($value) || $value === $defaultLocaleValue) {
                $newCurrentValue[$locale] = $newValue;
            }
        }

        $this->setValue($newCurrentValue);
    }

    public function render()
    {
        $defaultLocale = Locale::default();
        $currentValue = $this->getValue() ?? [];

        foreach(Locale::all() as $locale) {
            if(!$this->currentValueHasLocale($locale->code) || $this->localeValueIsEmpty($locale->code)) {
                $value = $this->currentValueHasLocale($locale->code) ? $currentValue[$defaultLocale->code] : '';

                $currentValue = array_merge($currentValue, [$locale->code => $value]);
            }
        }

        $this->setValue($currentValue);
    }

    protected function currentValueHasLocale($locale)
    {
        return array_key_exists($locale, $this->getValue() ?? []);
    }

    protected function localeValueIsEmpty($locale)
    {
        $currentValue = $this->getValue() ?? [];

        return empty($currentValue[$locale]);
    }
}