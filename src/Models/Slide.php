<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pieldefoca\Lux\Traits\HasMedia;
use Spatie\Translatable\HasTranslations;

class Slide extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasMedia;

    protected $table = 'lux_slides';

    protected $guarded = [];

    public array $translatable = [
        'title', 'subtitle', 'action_text', 'action_link',
    ];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background');
    }

    public function getBackground($locale = null)
    {
        if(is_null($locale)) $locale = app()->currentLocale();

        return $this->getFirstMedia('background', $locale);
    }

    public function getBackgroundUrl($locale = null)
    {
        if(is_null($locale)) $locale = app()->currentLocale();

        return $this->getBackground($locale)?->getUrl();
    }

    public function hasImageBackground($locale = null): bool
    {
        $types = ['image/bmp', 'image/gif', 'image/jpeg', 'image/png'];

        if(is_null($locale)) $locale = app()->currentLocale();

        return in_array($this->getBackground($locale)?->mime_type, $types);
    }

    public function hasVideoBackground($locale = null): bool
    {
        $types = ['video/x-msvideo', 'video/x-flv', 'video/quicktime', 'video/mpeg', 'video/mpeg', 'video/mpeg', 'video/quicktime', 'video/x-mpg'];

        if(is_null($locale)) $locale = app()->currentLocale();

        return in_array($this->getBackground($locale)?->mime_type, $types);
    }
}
