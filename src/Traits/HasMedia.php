<?php

namespace Pieldefoca\Lux\Traits;

use Pieldefoca\Lux\Models\Media;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Support\MediaManager\MediaAdder;
use Pieldefoca\Lux\Support\MediaManager\MediaManager;
use Pieldefoca\Lux\Support\MediaManager\MediaCollection;

trait HasMedia
{
    private $mediaManager;

    public function media()
    {
        return $this->morphToMany(Media::class, 'lux_mediable', 'lux_mediables', 'lux_mediable_id', 'lux_media_id')
            ->withPivot(['id', 'collection', 'locale', 'key'])
            ->withTimestamps();
    }

    protected function mediaManager()
    {
        if($this->mediaManager) return $this->mediaManager;

        $this->mediaManager = app()->make(MediaManager::class);

        return $this->mediaManager;
    }

    protected function mediaAdder()
    {
        return new MediaAdder($this);
    }

    public function addMediaCollection(string $name)
    {
        $manager = $this->mediaManager();

        $collection = $manager->addCollection($name, get_class($this));

        return $collection;
    }

    public function addMedia(array $mediaIds)
    {
        $this->registerMediaCollections();

        return $this->mediaAdder()->addMedia($mediaIds);
    }

    public function getFirstMedia(string $collection, ?string $locale = null, ?string $key = null): ?Media
    {
        if(!$this->isTranslatableCollection($collection)) {
            $locale = null;
        } else {
            if(is_null($locale)) $locale = config('lux.fallback_locale');
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
        $query = $this->media()->wherePivot('collection', $collection)->orderBy('lux_mediables.order');

        if($mediaType) {
            $query->where('media_type', $mediaType);
        }

        if($locale) {
            $query->where(function($query) use($locale) {
                $query->where('lux_mediables.locale', $locale);
                if($locale === config('lux.fallback_locale')) {
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

        foreach(config('lux.locales') as $locale) {
            $queryLocale = $this->isTranslatableCollection($collection)
                ? $locale->code
                : null;

            $media = $this->media()
                ->wherePivot('locale', $queryLocale)
                ->wherePivot('collection', $collection)
                ->orderBy('order')
                ->get();
            
            $translations[$locale->code] = $media->pluck('id')->toArray();
        }

        return $translations;
    }

    public function getCollection(string $name): ?MediaCollection
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

    public function isSingleFileCollection(string $collection)
    {
        return $this->getCollection($collection)->isSingleFile();
    }
}
