<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pieldefoca\Lux\Enum\SliderField;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'lux_sliders';

    protected $guarded = [];

    protected $casts = [
        'fields' => 'array'
    ];

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function hasTitleField(): bool
    {
        return in_array(SliderField::Title->value, $this->fields);
    }

    public function hasSubtitleField(): bool
    {
        return in_array(SliderField::Subtitle->value, $this->fields);
    }

    public function hasActionField(): bool
    {
        return in_array(SliderField::Action->value, $this->fields);
    }
}
