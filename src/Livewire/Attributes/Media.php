<?php

namespace Pieldefoca\Lux\Livewire\Attributes;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Models\Locale;
use Livewire\Attribute as LivewireAttribute;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

#[\Attribute]
class Media extends LivewireAttribute
{
    public function __construct(
        public string $collection,
        protected bool $translatable = false,
        bool $multiple = false,
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
                    "$propertyName" => $otherRules,
                    "{$propertyName}.{$defaultLocale}" => ['required'],
                ]);
            }
        }
    }

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

        if($this->translatable) {
            foreach(Locale::all() as $locale) {
                if(!array_key_exists($locale->code, $currentValue) || empty($currentValue[$locale->code])) {
                    $value = array_key_exists($defaultLocale->code, $currentValue)
                        ? (empty($currentValue[$defaultLocale->code]) ? [] : $currentValue[$defaultLocale->code])
                        : [];
    
                    $currentValue = array_merge($currentValue, [
                        $locale->code => $value,
                    ]);
                }
            }
        } else {
            if(empty($currentValue)) {
                $currentValue = [];
            }
        }

        $this->setValue($currentValue);
    }
}