<?php

namespace Pieldefoca\Lux\Support\MediaManager;

use Illuminate\Support\Str;
use Pieldefoca\Lux\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaManager
{
    protected array $collections = [];

    public function addCollection(string $name, string $model)
    {
        $collection = new MediaCollection($name);

        if(array_key_exists($model, $this->collections)) {
            $this->collections[$model][] = $collection;
        } else {
            $this->collections[$model] = [$collection];
        }

        return $collection;
    }

    public function getCollection(string $name, string $model)
    {
        foreach($this->collections as $class => $collections) {
            foreach($collections as $collection) {
                if($model === $class && $collection->name === $name) {
                    return $collection;
                }
            }
        }
        
        return null;
    }

    public function save($files)
    {
        if(! is_array($files)) {
            $files = [$files];
        }

        foreach($files as $file) {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = Str::slug($name);
            $extension = $file->getClientOriginalExtension();
            $fullName = "$filename.$extension";

            $media = Media::create([
                'filename' => $filename,
                'mime_type' => $file->getMimeType(),
                'extension' => $extension,
            ]);

            $file->storeAs($media->id, $fullName, 'uploads');

            $media->createVariations();
        }
    }
}