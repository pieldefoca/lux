<?php

namespace Pieldefoca\Lux\Jobs;

use Illuminate\Bus\Queueable;
use Pieldefoca\Lux\Models\Media;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OptimizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Media $media)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->media->createVariations();
    }
}
