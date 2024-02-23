<?php

namespace Pieldefoca\Lux\Support\MediaManager;

use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\DB;

class MediaAdder
{
    public $model;

    public array $mediaIds;

    public string $collection;

    public bool $translatable = false;

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

    public function saveTranslations()
    {
        $this->translatable = true;

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

        if($this->translatable) {
            $this->saveWithTranslations();
        } else {
            $this->save();
        }
    }

    protected function saveWithTranslations()
    {
        $keys = array_keys($this->mediaIds);

        $localeCount = Locale::whereIn('code', $keys)->count();

        if($localeCount != count($keys)) {
            throw new \Exception('The array of ids that you are trying to add is not correctly formed');
        }
        
        foreach($this->mediaIds as $locale => $ids) {
            $currentMediables = DB::table('lux_mediables')
                ->where('lux_mediable_id', $this->model->id)
                ->where('lux_mediable_type', get_class($this->model))
                ->where('collection', $this->collection)
                ->where('locale', $locale)
                ->where('key', $this->key)
                ->get();

            $currentMediaIds = $currentMediables->pluck('lux_media_id');

            foreach($ids as $index => $id) {
                if($currentMediaIds->contains($id)) continue;

                DB::table('lux_mediables')
                    ->insert([
                        'lux_media_id' => $id,
                        'lux_mediable_id' => $this->model->id,
                        'lux_mediable_type' => get_class($this->model),
                        'locale' => $locale, 
                        'collection' => $this->collection, 
                        'key' => $this->key, 
                        'order' => ($index + 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            $idsToBeDeleted = $currentMediaIds->diff($ids);

            $idsToBeDeleted->each(function($id) use($locale) {
                DB::table('lux_mediables')
                    ->where('lux_mediable_id', $this->model->id)
                    ->where('lux_mediable_type', get_class($this->model))
                    ->where('locale', $locale)
                    ->where('lux_media_id', $id)
                    ->where('key', $this->key)
                    ->delete();
            });
        }
    }

    protected function save()
    {
        $mediaIds = array_values($this->mediaIds);

        $mediaIds = array_unique($mediaIds);

        foreach($mediaIds as $index => $id) {
            $data[$id] = ['collection' => $this->collection, 'key' => $this->key, 'order' => ($index + 1)];
        }
        
        $this->model->media()->attach($data);
    }
}