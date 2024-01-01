<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use ReflectionClass;
use ReflectionProperty;
use Pieldefoca\Lux\Models\Locale;
use Livewire\Attribute as LivewireAttribute;

#[\Attribute]
class Translatable extends LivewireAttribute
{
    public function __construct(public $required = false) {}

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
            if(!array_key_exists($locale->code, $currentValue) || empty($currentValue[$locale->code])) {
                $value = array_key_exists($defaultLocale->code, $currentValue)
                    ? $currentValue[$defaultLocale->code]
                    : '';

                $currentValue = array_merge($currentValue, [
                    $locale->code => $value,
                ]);
            }
        }

        $this->setValue($currentValue);
    }
}