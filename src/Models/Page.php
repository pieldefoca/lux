<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Database\Factories\BlogCategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'lux_pages';

    protected $guarded = [];

    protected $casts = [
        'is_home_page' => 'boolean',
        'visible' => 'boolean',
    ];

    public $translatable = ['slug', 'title', 'description'];
}
