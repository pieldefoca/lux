<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use Pieldefoca\Lux\Models\Locale;
use Livewire\Attribute as LivewireAttribute;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

#[\Attribute]
class Media extends LivewireAttribute
{
    public function __construct(
        public string $collection,
        protected bool $translatable = false,
    ) {}

    public function update($field, $value)
    {
        $splits = explode('.', $field);
        $fieldName = $splits[0];
        $component = $this->getComponent();
        $locale = $component->currentLocaleCode;
        $newValue = $this->getValue();
        if(str($field)->contains('.0')) {
            if($value instanceof TemporaryUploadedFile) {
                $fileName = pathinfo($value->getClientOriginalName(), PATHINFO_FILENAME);
                if($this->translatable) {
                    $newValue[$locale][0] = $value;
                    $newValue[$locale][1] = $fileName;
                    $newValue[$locale][4] = $value->temporaryUrl();
                } else {
                    $newValue[0] = $value;
                    $newValue[1] = $fileName;
                    $newValue[4] = $value->temporaryUrl();
                }

                $this->setValue($newValue);
            }
        }
    }

    public function render()
    {
        $defaultLocale = Locale::default();
        $currentValue = $this->getValue();

        $emptyValue = [null, '', '', '', ''];

        if($this->translatable) {
            foreach(Locale::all() as $locale) {
                if(!array_key_exists($locale->code, $currentValue) || empty($currentValue[$locale->code][0])) {
                    $value = array_key_exists($defaultLocale->code, $currentValue)
                        ? (empty($currentValue[$defaultLocale->code]) ? $emptyValue : $currentValue[$defaultLocale->code])
                        : $emptyValue;
    
                    $currentValue = array_merge($currentValue, [
                        $locale->code => $value,
                    ]);
                }
            }
        } else {
            if(empty($currentValue)) {
                $currentValue = $emptyValue;
            }
        }

        $this->setValue($currentValue);
    }
}