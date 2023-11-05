<?php

namespace Pieldefoca\Lux\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Database\Factories\BlogCategoryFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'lux_pages';

    protected $guarded = [];

    protected $casts = [
        'is_home_page' => 'boolean',
        'visible' => 'boolean',
    ];

    public $translatable = ['slug', 'title', 'description'];

    public function localizedRoute($locale = null)
    {
        if(is_null($locale)) $locale = app()->currentLocale();

        return '/' . $locale . '/' .$this->translate('slug', $locale);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('luxPages');
        $this->addMediaCollection('videos')->useDisk('luxPages');
        $this->addMediaCollection('files')->useDisk('luxPages');
    }

    public function langFilename(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->view}.php",
        );
    }

    public function langFileKey(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->view,
        );
    }
}
