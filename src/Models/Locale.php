<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Locale extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'lux_locales';

    protected $guarded = [];

    protected $casts = ['default' => 'boolean'];

    public $translatable = ['name'];

    public function flagUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => asset("vendor/lux/img/flags/{$this->flag}")
        );
    }

    public static function default()
    {
        return self::where('default', true)->first();
    }
}
