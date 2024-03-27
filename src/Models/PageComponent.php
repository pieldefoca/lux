<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Model;

class PageComponent extends Model
{
    protected $table = 'lux_page_components';

    protected $guarded = [];

    protected $casts = [
        'elements' => 'array',
    ];
}