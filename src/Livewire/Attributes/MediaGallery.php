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

    public function update($field, $value)
    {
        $currentValue = $this->getValue();

        array_push($currentValue, ...$value);

        $this->setValue([]);
        // $this->setValue($currentValue);
    }

    // public function update($field, $value)
    // {
    //     $splits = explode('.', $field);
    //     $index = $splits[1];
    //     $component = $this->getComponent();
    //     $locale = $component->currentLocaleCode;
    //     $newValue = $this->getValue();
    //     if(str($field)->endsWith('.0')) {
    //         if($value instanceof TemporaryUploadedFile) {
    //             $fileName = pathinfo($value->getClientOriginalName(), PATHINFO_FILENAME);
    //             if($this->translatable) {
    //                 $newValue[$index][$locale][0] = $value;
    //                 $newValue[$index][$locale][1] = $fileName;
    //                 $newValue[$index][$locale][4] = $value->temporaryUrl();
    //             } else {
    //                 $newValue[0] = $value;
    //                 $newValue[1] = $fileName;
    //                 $newValue[4] = $value->temporaryUrl();
    //             }

    //             $this->setValue($newValue);
    //         }
    //     }
    // }

    public function render()
    {
        $defaultLocale = Locale::default();
        $currentValue = $this->getValue();

        $emptyValue = [null, '', '', '', ''];

        if($this->translatable) {
            foreach($currentValue as $locale => $media) {
                foreach(Locale::all() as $locale) {
                    if(!array_key_exists($locale->code, $media) || empty($media[$locale->code])) {
                        $value = array_key_exists($defaultLocale->code, $media)
                            ? (empty($media[$defaultLocale->code]) ? $emptyValue : $media[$defaultLocale->code])
                            : $emptyValue;

                        $media = array_merge($media, [
                            $locale->code => $value,
                        ]);
                    }
                }
                $currentValue[$index] = $media;
            }
        } else {
            if(empty($currentValue)) {
                $currentValue = $emptyValue;
            }
        }

        $this->setValue($currentValue);
    }
}
