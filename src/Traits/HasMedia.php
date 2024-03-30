<?php

namespace Pieldefoca\Lux\Traits;

use Pieldefoca\Lux\Facades\Lux;
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

    public function addMedia(array $mediaIds): ?MediaAdder
    {
        $this->registerMediaCollections();

        return $this->mediaAdder()->addMedia($mediaIds);
    }

    public function getFirstMedia(string $collection, ?string $locale = null, ?string $key = null, $retry = true): ?Media
    {
        $this->registerMediaCollections();

        $query = $this->media()
            ->wherePivot('collection', $collection)
            ->where(function($query) use($locale) {
                $query->where('lux_mediables.locale', $locale);
                if($locale === Lux::fallbackLocale()) {
                    $query->orWhere('lux_mediables.locale', null);
                }
                return $query;
            });

        if($key) {
            $query->wherePivot('key', $key);
        }

        $result = $query->first();

        if($result) {
            return $result;
        } else if($retry) {
            if(is_null($locale)) {
                return $this->getFirstMedia($collection, Lux::currentLocale());
            } else if($locale !== Lux::fallbackLocale()) {
                return $this->getFirstMedia($collection, Lux::fallbackLocale(), retry: false);
            }
        }

        return null;
    }

    public function getMedia(string $collection, $locale = null, $mediaType = null, ?string $key = null, $retry = true)
    {
        $query = $this->media()->wherePivot('collection', $collection)->orderBy('lux_mediables.order');

        if($mediaType) {
            $query->where('media_type', $mediaType);
        }

        if($locale) {
            $query->where(function($query) use($locale) {
                $query->where('lux_mediables.locale', $locale);
                if($locale === Lux::fallbackLocale()) {
                    $query->orWhere('lux_mediables.locale', null);
                }
                return $query;
            });
        }

        if($key) {
            $query->wherePivot('key', $key);
        }
        
        $result = $query->get();

        if($result->isNotEmpty()) {
            return $result;
        } else if($retry) {
            if(is_null($locale)) {
                return $this->getMedia($collection, Lux::currentLocale(), $mediaType, $key, retry: true);
            } else if($locale !== Lux::fallbackLocale()) {
                return $this->getMedia($collection, Lux::fallbackLocale(), $mediaType, $key, retry: false);
            }
        }

        return collect();
    }

    public function getMediaIds(string $collection): array
    {
        $mediaIds = [];

        $media = $this->media()->wherePivot('collection', $collection)->get();
            
        $mediaIds = $media->pluck('id')->toArray();

        return $mediaIds;
    }

    public function getMediaTranslations(string $collection): array
    {
        $translations = [];

        foreach(config('lux.locales') as $locale) {
            $media = $this->media()
                ->wherePivot('locale', $locale)
                ->wherePivot('collection', $collection)
                ->orderBy('order')
                ->get();
            
            $translations[$locale] = $media->pluck('id')->toArray();
        }

        return $translations;
    }

    public function getCollection(string $name): ?MediaCollection
    {
        return $this->mediaManager()->getCollection($name, get_class($this));
    }

    public function clearMedia(string $collection, $locale = null): void
    {
        if($this->isTranslatableCollection($collection)) {
            $media = $this->media()->wherePivot('collection', $collection)->wherePivot('locale', null)->get();

            foreach($media as $m) { $m->pivot->delete(); }
        }

        $media = $this->media()
            ->wherePivot('collection', $collection)
            ->when($locale, function($query, $locale) {
                return $query->where('locale', $locale);
            })
            ->get();

        foreach($media as $m) {
            $m->pivot->delete();
        }
    }

    public function isTranslatableCollection(string $collection): bool
    {
        return $this->getCollection($collection)->isTranslatable();
    }

    public function isSingleFileCollection(string $collection): bool
    {
        return $this->getCollection($collection)->isSingleFile();
    }
}
