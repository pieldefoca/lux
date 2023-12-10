<?php

namespace Pieldefoca\Lux\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAvatar
{
    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function() {
                if(is_null($this->avatar)) return null;

                return Storage::disk('avatars')->url($this->avatar);
            }
        );
    }

    public function removeAvatar()
    {
        if(is_null($this->avatar)) return;

        Storage::disk('avatars')->delete($this->avatar);

        $this->update(['avatar' => null]);
    }
}
