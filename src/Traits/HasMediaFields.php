<?php

namespace Pieldefoca\Lux\Traits;

use Livewire\WithFileUploads;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Livewire\Attributes\MediaGallery;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait HasMediaFields
{
    use WithFileUploads;

    public function initMediaFields($model): void
    {
        $this->initSingleMediaFields($model);

        $this->initMediaGalleryFields($model);
    }

    protected function initSingleMediaFields($model)
    {
        $mediaProperties = $this->getSingleMediaProperties();

        foreach($mediaProperties as $property) {
            extract($property); // $name, $collection, $translatable

            if($translatable) {
                foreach(Locale::all() as $locale) {
                    $media = $model->getFirstMedia($collection, ['locale' => $locale->code]);

                    $this->$name[$locale->code] = is_null($media)
                        ? []
                        : [
                            $media->getUrl(),
                            $media->name,
                            $media->getCustomProperty('alt'),
                            $media->getCustomProperty('title'),
                            $media->getUrl(),
                        ];
                }
            } else {
                $media = $model->getFirstMedia($collection);

                $this->$name = is_null($media)
                    ? []
                    : [
                        $media->getUrl(),
                        $media->name,
                        $media->getCustomProperty('alt'),
                        $media->getCustomProperty('title'),
                        $media->getUrl(),
                    ];
            }
        }
    }

    protected function initMediaGalleryFields($model)
    {
        $mediaProperties = $this->getMediaGalleryProperties();

        foreach($mediaProperties as $property) {
            extract($property); // $name, $collection, $translatable

            if($translatable) {
                $defaultMedia = $model->getMedia($collection, ['locale' => Locale::default()->code]);

                foreach($defaultMedia as $media) {
                    $media = $model->getMedia($collection, ['key' => $media->getCustomProperty('key')]);
                    $mediaLocales = [];
                    foreach($media as $item) {
                        $mediaLocales[$item->getCustomProperty('locale')] = [
                            $item->getUrl(),
                            $item->name,
                            $item->getCustomProperty('alt'),
                            $item->getCustomProperty('title'),
                            $item->getUrl(),
                        ];
                    }
                    $this->$name[] = $mediaLocales;
                }
            } else {
                $media = $model->getMedia($collection);

                foreach($media as $mediaItem) {
                    $this->$name = [
                        $mediaItem->getUrl(),
                        $mediaItem->name,
                        $mediaItem->getCustomProperty('alt'),
                        $mediaItem->getCustomProperty('title'),
                        $mediaItem->getUrl(),
                    ];
                }
            }
        }
    }

    public function getSingleMediaProperties()
    {
        return $this->getPropertiesWithAttribute(Media::class);    
    }

    public function getMediaGalleryProperties()
    {
        return $this->getPropertiesWithAttribute(MediaGallery::class);
    }

    protected function getPropertiesWithAttribute($attributeClass)
    {
        $reflectionClass = new \ReflectionClass(new self());
        $properties = [];
        foreach($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes($attributeClass);
            if(!empty($attributes)) {
                $collection = $attributes[0]->getArguments()['collection'];
                $translatable = $attributes[0]->getArguments()['translatable'];
                $properties[] = [
                    'name' => $property->getName(), 
                    'collection' => $collection,
                    'translatable' => $translatable,
                ];
            }
        }
        return $properties;
    }

    public function clearMediaField($field)
    {
        $this->$field = [];
    }

    public function saveMediaFields($model): void
    {
        $this->saveSingleMediaFields($model);

        $this->saveMediaGalleryFields($model);
    }

    protected function saveSingleMediaFields($model)
    {
        $mediaProperties = $this->getSingleMediaProperties();

        foreach($mediaProperties as $property) {
            $field = $property['name'];
            $collection = $property['collection'];
            $translatable = $property['translatable'];

            if($translatable) {
                foreach(Locale::all() as $locale) {
                    $this->saveOrUpdate(
                        $model, 
                        $collection, 
                        $this->$field[$locale->code][0], 
                        $this->$field[$locale->code][1], 
                        $this->$field[$locale->code][2], 
                        $this->$field[$locale->code][3],
                        $locale->code,
                    );
                }
            } else {
                $this->saveOrUpdate($model, $collection, $this->$field[0], $this->$field[1], $this->$field[2], $this->$field[3]);
            }
        }
    }

    protected function saveMediaGalleryFields($model)
    {
        $mediaProperties = $this->getMediaGalleryProperties();

        foreach($mediaProperties as $property) {
            $field = $property['name'];
            $collection = $property['collection'];
            $translatable = $property['translatable'];

            foreach($this->$field as $media) {
                if($translatable) {
                    foreach(Locale::all() as $locale) {
                        $this->saveOrUpdate(
                            $model, 
                            $collection, 
                            $media[$locale->code][0], 
                            $media[$locale->code][1], 
                            $media[$locale->code][2], 
                            $media[$locale->code][3],
                            $locale->code,
                        );
                    }
                } else {
                    $this->saveOrUpdate($model, $collection, $media[0], $media[1], $media[2], $media[3]);
                }
            }
        }
    }

    protected function saveOrUpdate($model, $collection, $fileOrUrl, $name, $alt, $title, $locale = null)
    {

        $media = is_null($locale)
            ? $model->getFirstMedia($collection)
            : $model->getFirstMedia($collection, ['locale' => $locale]);

        if(is_string($fileOrUrl)) {
            $this->updateMediaMetaData($media, $name, $alt, $title);
        } else {
            if($media) $media->delete();

            $this->saveMedia($model, $fileOrUrl, $name, $alt, $title, $collection, $locale);
        }
    }

    protected function updateMediaMetaData($media, $name, $alt, $title)
    {
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
        $media->name = $name;
        $media->file_name = "{$name}.{$extension}";
        $media->setCustomProperty('alt', $alt);
        $media->setCustomProperty('title', $title);
        $media->save();
    }

    protected function saveMedia($model, $file, $name, $alt, $title, $collection, $locale = null)
    {
        if(!is_null($file)) {
            $filename = $name;
            $extension = $file->getClientOriginalExtension();
            $model->addMedia($file)
                ->preservingOriginal()
                ->usingFileName("{$filename}.{$extension}")
                ->usingName($name)
                ->withCustomProperties([
                    'locale' => $locale,
                    'alt' => $alt,
                    'title' => $title,
                ])
                ->toMediaCollection($collection);
        }
    }
}
