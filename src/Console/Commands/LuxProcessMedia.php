<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Pieldefoca\Lux\Models\Media;

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
        // Create the uploads folder

        // Copy placeholder image

        Media::create([
            'name' => ['es' => 'Placeholder'],
            'filename' => '_placeholder.jpg',
            'mime_type' => 'image/jpeg',
            'media_type' => 'image',
        ]);

        Artisan::call('lux:pages');
    }
}
