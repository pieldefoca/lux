<?php

namespace Pieldefoca\Lux\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAvatar
{
    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('avatar')
    //         ->singleFile();
    // }

    public function avatarUrl(): Attribute
    {
        return Attribute::make(get: fn() => $this->getFirstMedia('avatar')?->getUrl());
    }
}
