<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use Livewire\Attribute as LivewireAttribute;
use Pieldefoca\Lux\Models\Locale;

#[\Attribute]
class Translatable extends LivewireAttribute
{
    public function __construct(
        public $emptyValue = '',
        public $required = true,
        public $message = null,
    ) {}

    public function boot()
    {
        $rules = $this->component->getRules();

        $propertyName = $this->getName();

        if(array_key_exists($propertyName, $rules)) {
            if(in_array('required', $rules[$propertyName])) {
                $otherRules = array_filter($rules[$propertyName], fn($rule) => $rule !== 'required');
                $defaultLocale = Locale::default()->code;
                $this->component->addRulesFromOutside([
                    "$propertyName" => array_merge($otherRules, ['nullable']),
                    "{$propertyName}.{$defaultLocale}" => ['required'],
                ]);
            }
        }
    }

    public function render()
    {
        $defaultLocale = Locale::default();
        $currentValue = $this->getValue() ?? [];

        foreach(Locale::all() as $locale) {
            if(!array_key_exists($locale->code, $currentValue) || empty($currentValue[$locale->code])) {
                $value = array_key_exists($defaultLocale->code, $currentValue)
                    ? $currentValue[$defaultLocale->code]
                    : $this->emptyValue;

                $currentValue = array_merge($currentValue, [
                    $locale->code => $value,
                ]);
            }
        }

        $this->setValue($currentValue);
    }
}