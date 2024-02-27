<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Console\Command;
use Pieldefoca\Lux\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $media = Media::all();

        foreach($media as $m) {
            $originalImage = $m->original_image;

            if($originalImage === '_placeholder.jpg') continue;

            $path = Storage::disk('uploads')->path("._ogs/{$originalImage}");

            if(!file_exists($path)) continue;

            $extension = pathinfo($originalImage, PATHINFO_EXTENSION);
            $newPath = public_path("uploads/{$m->id}/{$m->filename}.{$extension}");

            if(!\File::exists(public_path("uploads/{$m->id}"))) {
                \File::makeDirectory(public_path("uploads/{$m->id}"));
            }

            \File::move($path, $newPath);

            $m->update(['extension' => $extension]);
            $m->createVariations();
        }
    }
}
