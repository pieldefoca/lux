<?php

namespace Pieldefoca\Lux\Support\MediaManager;

class MediaAdder
{
    public $model;

    public array $mediaIds;

    public string $collection;

    public string $locale;

    public ?string $key = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function addMedia($mediaIds)
    {
        if(count($mediaIds) === 0) return;

        $this->mediaIds = $mediaIds;

        return $this;
    }

    public function forLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function withKey($key)
    {
        $this->key = $key;

        return $this;
    }

    public function toCollection(string $collection)
    {
        $this->collection = $collection;

        if($this->model->isSingleFileCollection($collection)) {
            $this->model->clearMedia($collection);
        }

        $this->save();
    }

    protected function save()
    {
        $mediaIds = array_values($this->mediaIds);

        $mediaIds = array_unique($mediaIds);

        foreach($mediaIds as $index => $id) {
            $data[$id] = ['collection' => $this->collection, 'locale' => $this->locale, 'key' => $this->key, 'order' => ($index + 1)];
        }
        
        $this->model->media()->attach($data);
    }
}