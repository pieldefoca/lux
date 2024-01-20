<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Pieldefoca\Lux\Models\Media;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class LuxProcessMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:media:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process media';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach(Media::all() as $media) {
            if(is_null($media->original_image)) {
                $this->saveOriginal($media);
                Storage::disk('uploads')->delete($media->original_image);
            }

            $media->createVariations();
        }

    }

    protected function saveOriginal(Media $media)
    {
        $filename = $media->filename;
        $from = Storage::disk('uploads')->path($filename);
        $to = Storage::disk('uploads')->path("._ogs/{$filename}");
        copy($from, $to);
        $media->update(['original_image' => $filename]);
    }
}
