<?php

namespace Pieldefoca\Lux\Support\MediaManager;

class MediaManager
{
    protected array $collections;

    public function addCollection(string $name, string $model)
    {
        $collection = new MediaCollection($name);

        $this->collections[$model] = $collection;

        return $collection;
    }

    public function getCollection(string $name, string $model)
    {
        foreach($this->collections as $class => $collection) {
            if($model === $class && $collection->name === $name) {
                return $collection;
            }
        }
        
        return null;
    }
}