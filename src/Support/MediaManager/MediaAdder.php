<?php

namespace Pieldefoca\Lux\Support\MediaManager;

use Illuminate\Support\Facades\DB;
use Pieldefoca\Lux\Facades\Lux;

class MediaAdder
{
    public $model;

    public array $mediaIds;

    public string $collection;

    public ?string $locale = null;

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

    public function toCollection(string $collection): void
    {
        $this->collection = $collection;

        $this->save();
    }

    protected function save(): void
    {
        $isTranslatable = $this->model->isTranslatableCollection($this->collection);
        $isSingleFile = $this->model->isSingleFileCollection($this->collection);

        $mediaIds = array_values($this->mediaIds);

        $mediaIds = array_unique($mediaIds);

        $this->validateMediaIdsArray($mediaIds);

        if($isTranslatable) {
            $this->locale ??= Lux::currentLocale();

            if($isSingleFile) {
                $media = $this->model->media()->wherePivot('locale', $this->locale)->first();

                if($media) {
                    DB::table('lux_mediables')
                        ->where('id', $media->pivot->id)
                        ->update(['lux_media_id' => $mediaIds[0]]);
                } else {
                    $data[] = [
                        'lux_media_id' => $mediaIds[0],
                        'lux_mediable_id' => $this->model->id,
                        'lux_mediable_type' => get_class($this->model),
                        'collection' => $this->collection,
                        'locale' => $this->locale,
                        'key' => $this->key,
                        'order' => 1,
                    ];

                    DB::table('lux_mediables')->insert($data);
                }

                if($this->locale === Lux::fallbackLocale()) {
                    $media = $this->model->media()->wherePivot('locale', '!=', $this->locale)->wherePivot('lux_media_id', $mediaIds[0])->get();

                    foreach($media as $m) {
                        $m->pivot->delete();
                    }
                }
            } else {
                $this->model->clearMedia($this->collection, $this->locale);

                $data = [];

                foreach($mediaIds as $index => $id) {
                    $data[] = [
                        'lux_media_id' => $id,
                        'lux_mediable_id' => $this->model->id,
                        'lux_mediable_type' => get_class($this->model),
                        'collection' => $this->collection,
                        'locale' => $this->locale,
                        'key' => $this->key,
                        'order' => ($index + 1),
                    ];
                }
                DB::table('lux_mediables')->insert($data);

                if($this->locale === Lux::fallbackLocale()) {
                    foreach(Lux::locales() as $locale) {
                        if($locale === Lux::fallbackLocale()) continue;

                        $localeMediaIds = $this->model->getMediaTranslations($this->collection, $locale);

                        $diff = array_diff($localeMediaIds[Lux::fallbackLocale()], $mediaIds);

                        if(empty($diff)) {
                            $this->model->clearMedia($this->collection, $locale);
                        }
                    }
                }
            }
        } else {
            $this->model->clearMedia($this->collection);

            if($isSingleFile) {
                $this->model->media()->attach($this->mediaIds[0], ['collection' => $this->collection, 'locale' => null, 'key' => $this->key, 'order' => 1]);
            } else {
                $data = [];

                foreach($mediaIds as $index => $id) {
                    $data[$id] = ['collection' => $this->collection, 'locale' => null, 'key' => $this->key, 'order' => ($index + 1)];
                }

                $this->model->media()->attach($data);
            }
        }
    }

    protected function validateMediaIdsArray(array $mediaIds): void
    {
        if(! $this->model->isSingleFileCollection($this->collection)) return;

        if(count($mediaIds) === 0 || count($mediaIds) > 1) {
            throw new \Exception('Malformed array of media ids. Exactly 1 expected, ' . count($mediaIds) . ' given');
        }
    }
}