<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use Pieldefoca\Lux\Models\Locale;
use Livewire\Attribute as LivewireAttribute;

#[\Attribute]
class Media extends LivewireAttribute
{
    public function __construct(
        public string $collection,
        public bool $translatable = false,
        public bool $multiple = false,
    ) {}

    public function render()
    {
        $defaultLocale = Locale::default();
        $currentValue = $this->getValue();

        if($this->translatable) {
            $defaultLocaleValue = array_unique($currentValue[$defaultLocale->code] ?? []);
            foreach(Locale::all() as $locale) {
                if(!array_key_exists($locale->code, $currentValue)) {
                    $currentValue[$locale->code] = [];
                    continue;
                }

                if(empty($currentValue[$locale->code])) {
                    $currentValue[$locale->code] = $defaultLocaleValue;
                }
            }
        } else {
            if(empty($currentValue)) {
                $currentValue = [];
            }
        }

        $this->setValue($currentValue);
    }

    public function isTranslatable()
    {
        return $this->translatable;
    }

    public function isMultiple()
    {
        return $this->multiple;
    }
}