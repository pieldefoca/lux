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
            ->withPivot(['locale', 'key'])
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

    public function addMedia(array $mediaIds, string $collection, bool $translatable = false, ?string $key = null)
    {
        $this->registerMediaCollections();

        if(count($mediaIds) === 0) return;

        if($this->isSingleFileCollection($collection)) {
            $this->clearMedia($collection);
        }
        
        if($translatable) {
            foreach($mediaIds as $locale => $ids) {
                foreach($ids as $id) {
                    $data = [];
                    $data[$id] = ['locale' => $locale, 'collection' => $collection, 'key' => $key];
                    $this->media()->attach($data);
                }
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
        if(!$this->isTranslatableCollection($collection)) {
            $locale = null;
        }

        $query = $this->media()
            ->wherePivot('collection', $collection)
            ->wherePivot('locale', $locale);

        if($key) {
            $query->wherePivot('key', $key);
        }
        
        return $query->first();
    }

    public function getMedia(string $collection, $locale = null, $mediaType = null, ?string $key = null)
    {
        $query = $this->media()->wherePivot('collection', $collection);

        if($mediaType) {
            $query->where('media_type', $mediaType);
        }

        if($locale) {
            $query->where(function($query) use($locale) {
                $query->where('lux_mediables.locale', $locale);
                if($locale === Locale::default()->code) {
                    $query->orWhere('lux_mediables.locale', null);
                }
                return $query;
            });
        }

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
            $queryLocale = $this->isTranslatableCollection($collection)
                ? $locale->code
                : null;

            $media = $this->media()
                ->wherePivot('locale', $queryLocale)
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

    public function isTranslatableCollection(string $collection)
    {
        $media = $this->getMedia($collection);

        return $media->count() > 0 && $media->where('pivot.locale', null)->count() === 0;
    }
}
