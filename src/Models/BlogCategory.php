<?php

namespace Pieldefoca\Lux\Models;

use Spatie\MediaLibrary\HasMedia;
use Database\Factories\PostFactory;
use Pieldefoca\Lux\Enum\PostStatus;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Pieldefoca\Lux\Enum\SliderPosition;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\BlogCategoryFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    public const IMAGE_COLLECTION = 'image';

    protected $table = 'lux_blog_categories';

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    public $translatable = ['name', 'slug'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE_COLLECTION);
    }

    protected static function newFactory(): Factory
    {
        return BlogCategoryFactory::new();
    }

    public function getImageUrl($locale = null): ?string
    {
        if(is_null($locale)) {
            $locale = Locale::default()->code;
        }

        return $this->getFirstMedia(self::IMAGE_COLLECTION, ['locale' => $locale])?->getUrl();
    }
}
