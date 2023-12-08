<?php

namespace Pieldefoca\Lux\Traits;

use Pieldefoca\Lux\Models\Media;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Support\MediaManager\MediaCollection;
use Pieldefoca\Lux\Support\MediaManager\MediaManager;

trait HasMedia
{
    public function media()
    {
        return $this->morphToMany(Media::class, 'lux_mediable', 'lux_mediables', 'lux_mediable_id', 'lux_media_id')
            ->withTimestamps();
    }

    protected function mediaManager()
    {
        return app()->make(MediaManager::class);
    }

    public function addMediaCollection(string $name)
    {
        $manager = $this->mediaManager();

        $collection = $manager->addCollection($name, get_class($this));

        return $collection;
    }

    public function addMedia(array $mediaIds, string $collection, ?string $key = null)
    {
        $this->registerMediaCollections();

        if(count($mediaIds) === 0) return;

        if($this->isSingleFileCollection($collection)) {
            $this->clearMedia($collection);
        }
        
        $translatable = Locale::where('code', strval(array_keys($mediaIds)[0]))->exists();

        if($translatable) {
            foreach($mediaIds as $locale => $ids) {
                $data = [];
                foreach($ids as $id) {
                    $data[$id] = ['locale' => $locale, 'collection' => $collection, 'key' => $key];
                }
                $this->media()->attach($data);
            }
        } else {
            foreach($mediaIds as $id) {
                $data[$id] = ['collection' => $collection, 'key' => $key];
            }
            $this->media()->attach($data);
        }
    }

    public function getFirstMedia(string $collection, ?string $locale = null, ?string $key = null): ?Media
    {
        if(is_null($locale)) {
            $locale = app()->currentLocale();
        }

        $query = $this->media()
            ->wherePivot('collection', $collection)
            ->wherePivot('locale', $locale);

        if($key) {
            $query->wherePivot('key', $key);
        }
        
        return $query->first();
    }

    public function getMedia(string $collection, ?string $key = null)
    {
        $query = $this->media()->wherePivot('collection', $collection);

        if($key) {
            $query->wherePivot('key', $key);
        }
        
        return $query->get();
    }

    public function getMediaIds(string $collection)
    {
        $mediaIds = [];

        $media = $this->media()->wherePivot('collection', $collection)->get();
            
        $mediaIds = $media->pluck('id')->toArray();

        return $mediaIds;
    }

    public function getMediaTranslations(string $collection)
    {
        $translations = [];

        foreach(Locale::all() as $locale) {
            $media = $this->media()
                ->wherePivot('locale', $locale->code)
                ->wherePivot('collection', $collection)->get();
            
            $translations[$locale->code] = $media->pluck('id')->toArray();
        }

        return $translations;
    }

    protected function isSingleFileCollection(string $collection)
    {
        $collection = $this->getCollection($collection);

        if(is_null($collection)) return false;

        return $collection->hasSingleFile();
    }

    protected function getCollection(string $name): ?MediaCollection
    {
        return $this->mediaManager()->getCollection($name, get_class($this));
    }

    public function clearMedia(string $collection)
    {
        $media = $this->media()
            ->wherePivot('collection', $collection)
            ->get();

        foreach($media as $m) {
            $m->pivot->delete();
        }
    }
}
