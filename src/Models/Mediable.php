<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Mediable extends Pivot
{
    protected $table = 'lux_mediables';

    public function media()
    {
        return $this->belongsTo(Media::class, 'lux_media_id');
    }
}
