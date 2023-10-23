<?php

namespace Pieldefoca\Lux\Models;

use Database\Factories\PostFactory;
use Spatie\MediaLibrary\HasMedia;
use Pieldefoca\Lux\Enum\PostStatus;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Pieldefoca\Lux\Enum\SliderPosition;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'lux_posts';

    protected $guarded = [];

    protected $casts = [
        'status' => PostStatus::class,
        'featured' => 'boolean',
    ];

    public $translatable = ['title', 'slug', 'body'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featuredImage');
    }

    protected static function newFactory(): Factory
    {
        return PostFactory::new();
    }
}
