<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use Pieldefoca\Lux\Models\Locale;
use Livewire\Attribute as LivewireAttribute;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

#[\Attribute]
class MediaGallery extends LivewireAttribute
{
    public function __construct(
        public string $collection,
        protected bool $translatable = false,
    ) {}

    public function render()
    {
        $defaultLocale = Locale::default();
        $currentValue = $this->getValue();

        if($this->translatable) {
            foreach(Locale::all() as $locale) {
                if(!array_key_exists($locale->code, $currentValue)) {
                    $currentValue[$locale->code] = [];
                    continue;
                }

                if(empty($currentValue[$locale->code])) {
                    $currentValue[$locale->code] = $currentValue[$defaultLocale->code];
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
}
