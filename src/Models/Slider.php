<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pieldefoca\Lux\Enum\SliderPosition;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'lux_sliders';

    protected $guarded = [];

    protected $casts = [
        'position' => SliderPosition::class,
    ];

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }
}
