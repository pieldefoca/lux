<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Contact extends Model
{
	use HasFactory;
    use HasTranslations;

    protected $table = 'lux_contact';

	protected $guarded = [];

    public $translatable = ['opening_hours', 'address_line_1', 'address_line_2', 'address_line_3'];

    public function scopeGeneral(Builder $query): void
    {
        $query->where('type', 'general');
    }
}
