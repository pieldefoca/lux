<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'lux_media';

    protected $guarded = [];

    public $translatable = ['name', 'filename', 'alt', 'title'];
}
