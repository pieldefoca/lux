<?php

namespace Pieldefoca\Lux\Traits;

use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Pieldefoca\Lux\Facades\Lux;
use Pieldefoca\Lux\Livewire\Attributes\Media;

trait HasMediaFields
{
    use WithFileUploads;

    #[On('media-selected')]
    public function mediaSelected($field, $mediaIds)
    {
        $mediaProperties = $this->getMediaProperties();

        foreach($mediaProperties as $property) {
            if($field === $property['name']) {
                // if($property['translatable']) {
                //     $fallbackToDefault = [];

                //     if($this->locale === $this->defaultLocaleCode) {
                //         $defaultLocaleValue = array_unique($this->$field[$this->defaultLocaleCode]);

                //         foreach($this->$field as $locale => $ids) {
                //             if($locale === $this->defaultLocaleCode) continue;

                //             $idsNotPresentInDefaultLocaleValue = array_diff(array_unique($ids), $defaultLocaleValue);

                //             if(count($idsNotPresentInDefaultLocaleValue) === 0) {
                //                 $fallbackToDefault[] = $locale;
                //             }
                //         }
                //     }

                //     $newValue = $mediaIds;

                //     if($property['multiple']) {
                //         $newValue = array_merge($this->$field[$this->locale], $mediaIds);
                //         $newValue = array_unique($newValue);
                //         $newValue = array_values($newValue);
                //         $this->$field[$this->locale] = $newValue;
                //     } else {
                //         $this->$field[$this->locale] = $mediaIds;
                //     }

                //     foreach($fallbackToDefault as $locale) {
                //         $this->$field[$locale] = $newValue;
                //     }
                // } else {
                    $this->$field = $mediaIds;
                // }
            }
        }
    }

    protected function initMediaFields($model)
    {
        $mediaProperties = $this->getMediaProperties();

        foreach($mediaProperties as $property) {
            extract($property); // $name, $collection, $multiple

            if($multiple) {
                $this->$name = $model->getMedia($collection, Lux::currentLocale())->pluck('id')->toArray();
            } else {
                $media = $model->getFirstMedia($collection);

                $this->$name = is_null($media) ? [] : [$media->id];
            }
        }
    }

    public function getMediaProperties()
    {
        return $this->getPropertiesWithAttribute(Media::class);
    }

    protected function getPropertiesWithAttribute($attributeClass)
    {
        $reflectionClass = new \ReflectionClass(new self());
        $properties = [];
        foreach($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes($attributeClass);
            if(!empty($attributes)) {
                $collection = $attributes[0]->getArguments()['collection'];
                $multiple = $attributes[0]->newInstance()->isMultiple();
                $properties[] = [
                    'name' => $property->getName(), 
                    'collection' => $collection,
                    'multiple' => $multiple,
                ];
            }
        }
        return $properties;
    }

    public function clearMediaField($field)
    {
        if(str($field)->contains('.')) {
            $splits = explode('.', $field);
            $field = $splits[0];
            $this->$field[$splits[1]] = [];
        } else {
            $this->$field = [];
        }
    }

    public function unselectMedia($field, $mediaId)
    {
        $fieldSplits = explode('.', $field);

        if(count($fieldSplits) > 1) {
            $field = $fieldSplits[0];
            $locale = $fieldSplits[1];
            $index = array_search($mediaId, $this->$field[$locale]);
            unset($this->$field[$locale][$index]);
            array_values($this->$field[$locale]);
        } else {
            $index = array_search($mediaId, $this->$field);
            unset($this->$field[$index]);
            array_values($this->$field);
        }
    }

    public function reorderGallery($field, $ids)
    {
        $this->$field[$this->locale] = $ids;
    }
}
