<?php

namespace Pieldefoca\Lux\Observers;

use Pieldefoca\Lux\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaObserver
{
    /**
     * Handle the Media "created" event.
     */
    public function created(Media $media): void
    {
        $media->createVariations();
    }

    /**
     * Handle the Media "updated" event.
     */
    public function updated(Media $media): void
    {
        //
    }

    /**
     * Handle the Media "deleted" event.
     */
    public function deleted(Media $media): void
    {
        $media->deleteAll();
    }

    /**
     * Handle the Media "restored" event.
     */
    public function restored(Media $media): void
    {
        //
    }

    /**
     * Handle the Media "force deleted" event.
     */
    public function forceDeleted(Media $media): void
    {
        //
    }
}
