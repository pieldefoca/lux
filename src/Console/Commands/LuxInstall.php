<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Console\Command;
use Pieldefoca\Lux\Models\Media;

class LuxInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize lux stuff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Media::create([
            'filename' => '_placeholder',
            'mime_type' => 'image/webp',
            'media_type' => 'image',
            'extension' => 'webp',
        ]);
    }
}
